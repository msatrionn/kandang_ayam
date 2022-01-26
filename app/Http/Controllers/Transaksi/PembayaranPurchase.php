<?php

namespace App\Http\Controllers\Transaksi;

use App\Http\Controllers\Controller;
use App\Models\Auth\User;
use App\Models\Master\Produk;
use App\Models\Master\Setup;
use App\Models\Transaksi\HeaderTrans;
use App\Models\Transaksi\ListTrans;
use App\Models\Transaksi\LogTrans;
use App\Models\Transaksi\Purchase;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Tanggal;
use PDF;

class PembayaranPurchase extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        if (User::setIjin('Pembayaran Purchase')) {
            if ($request->key == 'purchase') {
                $data   =   Purchase::orderBy('tanggal', 'DESC')
                            ->where(function($query) use ($request){
                                if ($request->pay) {
                                    $query->where('id', $request->pay);
                                }
                            })
                            ->where('status', 1)
                            ->paginate(8);

                return view('transaksi.kas_keluar.daftar_purchase', compact('data', 'request'));
            } else

            if ($request->key == 'unduh') {
                if ($request->mulai || $request->selesai) {
                    $trans  =   LogTrans::where('jenis', 'pelunasan')
                                ->whereBetween('tanggal', [$request->mulai, $request->selesai])
                                ->orderByRaw('tanggal DESC, id DESC')
                                ->get();

                    header("Content-type: application/csv");
                    header("Content-Disposition: attachment; filename=Pembayaran Purchase Order Periode " . Tanggal::date($request->mulai) . " - " . Tanggal::date($request->selesai) . ".csv");
                    $fp = fopen('php://output', 'w');
                    fputcsv($fp, ["sep=,"]);

                    $data   =   [
                        "No",
                        "Tanggal",
                        "Nomor PO",
                        "Supplier",
                        "Nominal Pembayaran",
                        "Metode Pembayaran",
                    ];
                    fputcsv($fp, $data);

                    foreach ($trans as $i => $row):
                        $data   =   [
                            ++$i,
                            $row->tanggal,
                            $row->purchase->nomor_purchasing,
                            $row->purchase->supplier->nama,
                            $row->nominal,
                            $row->method->nama ?? '',
                        ];

                        fputcsv($fp, $data);
                    endforeach;

                    fclose($fp);

                    return '';
                    // return view('transaksi.kas_keluar.excel', compact('trans', 'request'));
                } else {
                    return back()->with('error', "Pilih tanggal untuk unduh data");
                }

            } else

            if ($request->key == 'input') {
                $pay    =   Setup::where('slug', 'payment')->pluck('nama', 'id');
                return view('transaksi.kas_keluar.input_kas', compact('pay'));
            } else

            if ($request->key == 'riwayat') {
                $trans  =   LogTrans::where('jenis', 'pelunasan')
                            ->orderByRaw('tanggal DESC, id DESC')
                            ->paginate(9);

                $pay    =   Setup::where('slug', 'payment')->pluck('nama', 'id');

                return view('transaksi.kas_keluar.riwayat', compact('trans', 'pay'));
            } else

            {
                return view('transaksi.kas_keluar.index', compact('request'));
            }
        }
        return redirect()->route('home')->with('error', 'Anda tidak memiliki hak akses ke halaman tersebut');
    }

    public function pdf($id)
    {
        if (User::setIjin('Pembayaran Purchase')) {
            $trans  =   LogTrans::where('jenis', 'pelunasan')
                        ->where('id', $id)
                        ->first() ;

            if ($trans) {

                // $pdf    =   App::make('dompdf.wrapper');
                // $pdf->loadHTML(view('transaksi.kas_keluar.pdf', compact('trans')))->setPaper('A5', 'landscape');
                // return $pdf->stream();

                $pdf    =   PDF::loadHTML(view('transaksi.kas_keluar.pdf', compact('trans')))->setPaper('A5', 'landscape');
                $pdf->getDomPDF()->set_option("enable_php", true);
                return $pdf->download('Bukti Kas Keluar ' . $trans->nomor_transaksi . '.pdf');
            }

            return redirect()->route('paypurchase.index');
        }
        return redirect()->route('home')->with('error', 'Anda tidak memiliki hak akses ke halaman tersebut');
    }

    public function store(Request $request)
    {
        if (User::setIjin('Pembayaran Purchase')) {
            if ($request->key == 'input') {
                if ($request->check_kas) {
                    $this->validate($request, [
                        'purchase'              =>  ['required', Rule::exists('purchase', 'id')->where('status', 1)],
                        'tanggal_pembayaran'    =>  'required|date',
                        'tulis_pembayaran'      =>  'required|string',
                        'nominal_dibayarkan'    =>  'required',
                    ]);
                } else {
                    $this->validate($request, [
                        'purchase'              =>  ['required', Rule::exists('purchase', 'id')->where('status', 1)],
                        'tanggal_pembayaran'    =>  'required|date',
                        'metode_pembayaran'     =>  ['required', Rule::exists('setup', 'id')->where('slug', 'payment')],
                        'nominal_dibayarkan'    =>  'required',
                    ]);
                }


                DB::beginTransaction() ;

                $purchase               =   Purchase::find($request->purchase) ;
                $purchase->dibayarkan   =   ($purchase->dibayarkan ?? 0) + str_replace(',', '', $request->nominal_dibayarkan) ;
                $purchase->status       =   ($purchase->dibayarkan >= ($purchase->total_harga + ($purchase->tax ? 0 : $purchase->total_harga * (10/100)) - $purchase->down_payment)) ? 2 : 1 ;
                if (!$purchase->save()) {
                    DB::rollBack() ;
                }

                $log                =   new LogTrans ;
                $log->table         =   'purchase';
                $log->table_id      =   $purchase->id;
                $log->produk_id     =   $purchase->produk_id ;
                $log->jenis         =   'pelunasan';
                $log->tanggal       =   $request->tanggal_pembayaran;

                if ($request->check_kas) {
                    $payment            =   new Setup;
                    $payment->slug      =   'payment';
                    $payment->nama      =   $request->tulis_pembayaran;
                    $payment->status    =   2;
                    if (!$payment->save()) {
                        DB::rollBack() ;
                    }

                    $log->kas       =   $payment->id;
                } else {
                    $log->kas       =   $request->metode_pembayaran;
                }

                $log->nominal       =   str_replace(',', '', $request->nominal_dibayarkan) ;
                $log->status        =   1;
                if (!$log->save()) {
                    DB::rollBack();
                }

                DB::commit() ;
            }

            if ($request->key == 'update') {
                $this->validate($request, [
                    'row_id'                =>  ['required', Rule::exists('log_trans', 'id')->where('jenis', 'pelunasan')->where('status', 1)],
                    'tanggal_bayar'         =>  'required|date',
                    'metode_bayar'          =>  ['required', Rule::exists('setup', 'id')->where('slug', 'payment')],
                    'nominal_dibayarkan'    =>  'required',
                ]);

                $log                =   LogTrans::find($request->row_id) ;
                $log->tanggal       =   $request->tanggal_bayar ;
                $log->kas           =   $request->metode_bayar ;

                $purc               =   Purchase::find($log->table_id) ;
                $purc->dibayarkan   =   ($purc->dibayarkan - $log->nominal) + str_replace(',', '', $request->nominal_dibayarkan) ;
                $purc->status       =   (($purc->dibayarkan + $purc->down_payment) >= $purc->total_harga) ? 2 : 1 ;
                $purc->save() ;

                $log->nominal       =   str_replace(',', '', $request->nominal_dibayarkan) ;
                $log->save() ;
            }
        }
        return redirect()->route('home')->with('error', 'Anda tidak memiliki hak akses ke halaman tersebut');
    }
}

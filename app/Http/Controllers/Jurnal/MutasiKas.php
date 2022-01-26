<?php

namespace App\Http\Controllers\Jurnal;

use App\Http\Controllers\Controller;
use App\Models\Auth\User;
use App\Models\Master\Setup;
use App\Models\Transaksi\HeaderTrans;
use App\Models\Transaksi\ListTrans;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Tanggal ;

class MutasiKas extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        if (User::setIjin('Jurnal Mutasi Kas')) {
            if ($request->key == 'riwayat_mutasi') {
                $q          =   $request->search ?? '' ;

                $mutasi     =   HeaderTrans::where('jenis', 'mutasi_keluar')
                                ->where('status', 1)
                                ->orderByRaw('id DESC, tanggal DESC')
                                ->get() ;

                $mutasi     =   $mutasi->filter(function($item) use($q){
                    $res = true;
                    if ($q != "") {
                        $res =  (false !== stripos($item->method->nama, $q)) ||
                                (false !== stripos($item->child->method->nama, $q)) ||
                                (false !== stripos($item->payment, $q)) ||
                                (false !== stripos(number_format($item->payment), $q)) ||
                                (false !== stripos(Tanggal::date($item->tanggal), $q)) ||
                                (false !== stripos($item->tanggal, $q));

                    }
                    return $res;
                });

                $mutasi     =   $mutasi->paginate(10) ;

                return view('jurnal.mutasi_kas.riwayat', compact('mutasi', 'q')) ;
            } else

            if ($request->key == 'input_mutasi') {
                $payment    =   Setup::where('slug', 'payment')
                                ->pluck('nama', 'id');

                return view('jurnal.mutasi_kas.input', compact('payment')) ;
            } else

            if ($request->key == 'unduh') {
                if ($request->mulai || $request->selesai) {
                    $mutasi     =   HeaderTrans::where('jenis', 'mutasi_keluar')
                                    ->whereBetween('tanggal', [$request->mulai, $request->selesai])
                                    ->where('status', 1)
                                    ->orderByRaw('id DESC, tanggal DESC')
                                    ->get() ;

                    header("Content-type: application/csv");
                    header("Content-Disposition: attachment; filename=Jurnal Mutasi Kas Periode " . Tanggal::date($request->mulai) . " - " . Tanggal::date($request->selesai) . ".csv");
                    $fp = fopen('php://output', 'w');
                    fputcsv($fp, ["sep=,"]);

                    $data   =   [
                        "No",
                        "Tanggal",
                        "Dari Kas",
                        "Transfer ke Kas",
                        "Nominal",
                    ];
                    fputcsv($fp, $data);

                    foreach ($mutasi as $i => $row) {
                        $data   =   [
                            ++$i,
                            $row->tanggal,
                            $row->method->nama,
                            $row->child->method->nama,
                            $row->payment,
                        ];
                        fputcsv($fp, $data);
                    }

                    fclose($fp);
                    return '';
                }
                return back()->with('error', 'Pilih tanggal untuk unduh data');
            }

            else {
                return view('jurnal.mutasi_kas.index');
            }
        }
        return redirect()->route('home')->with('error', 'Anda tidak memiliki hak akses ke halaman tersebut');
    }

    public function store(Request $request)
    {
        if (User::setIjin('Jurnal Mutasi Kas')) {
            if (!$request->tanggal_mutasi) {
                $result['status']       =   400;
                $result['msg']          =   "Tanggal wajib diisikan";
                return $result ;
            }

            $nominal    =   str_replace(',','', $request->nominal_mutasi);

            $dari_kas   =   Setup::where('id', $request->dari_kas)
                            ->where('slug', 'payment')
                            ->first() ;

            if (!$dari_kas) {
                $result['status']       =   400;
                $result['msg']          =   "Dari kas tidak ditemukan";
                return $result ;
            } else {
                if ($nominal > Setup::hitung_kas($dari_kas->id)) {
                    $result['status']       =   400;
                    $result['msg']          =   "Nominal mutasi terlalu besar";
                    return $result ;
                }
            }

            $transfer_ke=   Setup::where('id', $request->transfer_ke)
                            ->where('slug', 'payment')
                            ->first() ;

            if (!$transfer_ke) {
                $result['status']       =   400;
                $result['msg']          =   "Transfer ke tidak ditemukan";
                return $result ;
            }

            DB::beginTransaction() ;

            $keluar                 =   new HeaderTrans ;
            $keluar->jenis          =   'mutasi_keluar' ;
            $keluar->nomor          =   HeaderTrans::ambil_nomor($keluar->jenis) ;
            $keluar->user_id        =   Auth::user()->id ;
            $keluar->tanggal        =   $request->tanggal_mutasi ;
            $keluar->total_trans    =   $nominal ;
            $keluar->payment        =   $nominal ;
            $keluar->payment_method =   $dari_kas->id ;
            $keluar->status         =   1;
            if (!$keluar->save()) {
                DB::rollBack();
                $result['status']   =   400;
                $result['msg']      =   "Proses gagal";
                return $result ;
            }

            $list_keluar                =   new ListTrans;
            $list_keluar->header_id     =   $keluar->id ;
            $list_keluar->type          =   $keluar->jenis ;
            $list_keluar->harga_satuan  =   $nominal ;
            $list_keluar->total_harga   =   $nominal ;
            if (!$list_keluar->save()) {
                DB::rollBack();
                $result['status']   =   400;
                $result['msg']      =   "Proses gagal";
                return $result ;
            }

            $masuk                  =   new HeaderTrans ;
            $masuk->parent          =   $keluar->id ;
            $masuk->jenis           =   'mutasi_masuk' ;
            $masuk->nomor           =   HeaderTrans::ambil_nomor($masuk->jenis) ;
            $masuk->user_id         =   Auth::user()->id ;
            $masuk->tanggal         =   $request->tanggal_mutasi ;
            $masuk->total_trans     =   $nominal ;
            $masuk->payment         =   $nominal ;
            $masuk->payment_method  =   $transfer_ke->id ;
            $masuk->status          =   1;
            if (!$masuk->save()) {
                DB::rollBack();
                $result['status']   =   400;
                $result['msg']      =   "Proses gagal";
                return $result ;
            }

            $list_masuk                 =   new ListTrans;
            $list_masuk->header_id      =   $masuk->id ;
            $list_masuk->type           =   $masuk->jenis ;
            $list_masuk->harga_satuan   =   $nominal ;
            $list_masuk->total_harga    =   $nominal ;
            if (!$list_masuk->save()) {
                DB::rollBack();
                $result['status']   =   400;
                $result['msg']      =   "Proses gagal";
                return $result ;
            }

            DB::commit() ;
        }
        return redirect()->route('home')->with('error', 'Anda tidak memiliki hak akses ke halaman tersebut');
    }

    public function destroy(Request $request)
    {
        if (User::setIjin('Jurnal Mutasi Kas')) {
            $keluar             =   HeaderTrans::where('id', $request->id)
                                    ->where('status', 1)
                                    ->where('jenis', 'mutasi_keluar')
                                    ->first() ;

            if ($keluar) {
                $masuk              =   HeaderTrans::where('parent', $request->id)
                                        ->where('status', 1)
                                        ->where('jenis', 'mutasi_masuk')
                                        ->first() ;

                $masuk->status      =   0 ;
                $masuk->save() ;

                $keluar->status     =   0 ;
                $keluar->save() ;
            } else {
                $result['status']   =   400;
                $result['msg']      =   "Data mutasi tidak ditemukan";
                return $result ;
            }
        }
        return redirect()->route('home')->with('error', 'Anda tidak memiliki hak akses ke halaman tersebut');
    }
}

<?php

namespace App\Http\Controllers\Transaksi;

use App\Http\Controllers\Controller;
use App\Models\Auth\User;
use App\Models\Master\Produk;
use App\Models\Master\Setup;
use App\Models\Master\Supplier;
use App\Models\Transaksi\Delivery;
use App\Models\Transaksi\HeaderTrans;
use App\Models\Transaksi\ListTrans;
use App\Models\Transaksi\LogTrans;
use App\Models\Transaksi\Purchase;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Tanggal;
use PDF;

class PurchasingOrder extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        if (User::setIjin('Purchasing Order')) {
            $q      =   $request->cari ?? '';

            $produk = Produk::where('nama', 'LIKE', '%' . $q . '%')->get();
            // dd($produk);
            foreach ($produk as $key => $value) {
                $produks[] = $value->id;
            }
            // dd($produks);
            $data = Purchase::orderByRaw('nomor DESC, tanggal DESC')
                ->get();
            if ($q != "") {
                $data   =   $data->filter(
                    function ($item) use ($q) {
                        $res = true;
                        if ($q != "") {
                            $res =  (false !== stripos($item->total_harga, $q)) ||
                                (false !== stripos($item->nomor_purchasing, $q)) ||
                                (false !== stripos(($item->terkirim ? "Delivery " . $item->terkirim : ""), $q)) ||
                                (false !== stripos(($item->terkirim ? "Delivery " . number_format($item->terkirim) : ""), $q)) ||
                                (false !== stripos(Tanggal::date($item->tanggal), $q)) ||
                                (false !== stripos($item->tanggal, $q));
                        }
                        return $res;
                    }
                );
                if (!empty($produks)) {
                    foreach ($produks as $word) {
                        $data = Purchase::orderByRaw('nomor DESC, tanggal DESC')
                            ->where('produk', 'LIKE', '%' . '"' . 'produk' . '"' . ':' . '"' . $word . '"' . '%')
                            ->get();
                    }
                    // $data   =   Purchase::orderByRaw('nomor DESC, tanggal DESC')
                    //     ->where('produk', 'LIKE', '%' . '"' . 'produk' . '"' . ':' . '"' . $produk->id . '"' . '%')
                    //     ->orWhere('total_harga', 'LIKE', '%' .  $q  . '%')
                    //     ->get();
                }
                // return $data;
            }
            // dd($data);
            // $data   =   Purchase::orderByRaw('nomor DESC, tanggal DESC')
            //     ->get();
            // $data   =   $data->filter(function ($item) use ($q) {
            //     $res = true;
            //     if ($q != "") {
            //         $res =  (false !== stripos($item->total_harga, $q)) ||
            //             (false !== stripos($item->nomor_purchasing, $q)) ||
            //             (false !== stripos(($item->terkirim ? "Delivery " . $item->terkirim : ""), $q)) ||
            //             (false !== stripos(($item->terkirim ? "Delivery " . number_format($item->terkirim) : ""), $q)) ||
            //             (false !== stripos(Tanggal::date($item->tanggal), $q)) ||
            //             (false !== stripos($item->tanggal, $q));
            //     }
            //     // dd($res);
            // });

            $data       =   $data->paginate(15);

            $produk     =   Produk::orderBy('nama', 'ASC')
                ->where('jenis', 'purchase')
                ->get();

            $payment    =   Setup::where('slug', 'payment')
                ->orderBy('nama', 'ASC')
                ->pluck('nama', 'id');

            $kandang    =   Setup::where('slug', 'kandang')
                ->orderBy('nama', 'ASC')
                ->pluck('nama', 'id');

            $supplier   =   Supplier::orderBy('nama', 'ASC')->pluck('nama', 'id');

            return view('transaksi.purchasing_order.index', compact('produk', 'q', 'data', 'payment', 'supplier', 'kandang'));
        }
        return redirect()->route('home')->with('error', 'Anda tidak memiliki hak akses ke halaman tersebut');
    }
    public function show_index(Request $request)
    {
        if (User::setIjin('Purchasing Order')) {
            $q      =   $request->cari ?? '';

            $produk = Produk::where('nama', 'LIKE', '%' . $q . '%')->get();
            // dd($produk);
            foreach ($produk as $key => $value) {
                $produks[] = $value->id;
            }
            // dd($produks);
            $data = Purchase::orderByRaw('nomor DESC, tanggal DESC')
                ->get();
            if ($q != "") {
                $data   =   $data->filter(
                    function ($item) use ($q) {
                        $res = true;
                        if ($q != "") {
                            $res =  (false !== stripos($item->total_harga, $q)) ||
                                (false !== stripos($item->nomor_purchasing, $q)) ||
                                (false !== stripos(($item->terkirim ? "Delivery " . $item->terkirim : ""), $q)) ||
                                (false !== stripos(($item->terkirim ? "Delivery " . number_format($item->terkirim) : ""), $q)) ||
                                (false !== stripos(Tanggal::date($item->tanggal), $q)) ||
                                (false !== stripos($item->tanggal, $q));
                        }
                        return $res;
                    }
                );
                if (!empty($produks)) {
                    foreach ($produks as $word) {
                        $data = Purchase::orderByRaw('nomor DESC, tanggal DESC')
                            ->where('produk', 'LIKE', '%' . '"' . 'produk' . '"' . ':' . '"' . $word . '"' . '%')
                            ->get();
                    }
                    // $data   =   Purchase::orderByRaw('nomor DESC, tanggal DESC')
                    //     ->where('produk', 'LIKE', '%' . '"' . 'produk' . '"' . ':' . '"' . $produk->id . '"' . '%')
                    //     ->orWhere('total_harga', 'LIKE', '%' .  $q  . '%')
                    //     ->get();
                }
                // return $data;
            }
            // dd($data);
            // $data   =   Purchase::orderByRaw('nomor DESC, tanggal DESC')
            //     ->get();
            // $data   =   $data->filter(function ($item) use ($q) {
            //     $res = true;
            //     if ($q != "") {
            //         $res =  (false !== stripos($item->total_harga, $q)) ||
            //             (false !== stripos($item->nomor_purchasing, $q)) ||
            //             (false !== stripos(($item->terkirim ? "Delivery " . $item->terkirim : ""), $q)) ||
            //             (false !== stripos(($item->terkirim ? "Delivery " . number_format($item->terkirim) : ""), $q)) ||
            //             (false !== stripos(Tanggal::date($item->tanggal), $q)) ||
            //             (false !== stripos($item->tanggal, $q));
            //     }
            //     // dd($res);
            // });

            $data       =   $data->paginate(15);

            $produk     =   Produk::orderBy('nama', 'ASC')
                ->where('jenis', 'purchase')
                ->get();

            $payment    =   Setup::where('slug', 'payment')
                ->orderBy('nama', 'ASC')
                ->pluck('nama', 'id');

            $kandang    =   Setup::where('slug', 'kandang')
                ->orderBy('nama', 'ASC')
                ->pluck('nama', 'id');

            $supplier   =   Supplier::orderBy('nama', 'ASC')->pluck('nama', 'id');

            return view('transaksi.purchasing_order.show_index', compact('produk', 'q', 'data', 'payment', 'supplier', 'kandang'));
        }
        return redirect()->route('home')->with('error', 'Anda tidak memiliki hak akses ke halaman tersebut');
    }

    public function store(Request $request)
    {
        if (User::setIjin('Purchasing Order')) {
            $cek_valid  =   [
                // "produk"            =>  ['required', Rule::exists('produk', 'id')->where('jenis', 'purchase')],
                // "harga_satuan"      =>  'required|numeric',
                // "jumlah_purchase"   =>  'required|numeric',
                "tanggal_purchase"  =>  'required|date',
                // "termin"            =>  'required|numeric',
            ];

            $down_pay   =   [];
            if ($request->down_payment) {
                if ($request->check_kas) {
                    $down_pay   =   [
                        "tulis_pembayaran"  =>  'required|string',
                        "down_payment"      =>  'required|numeric',
                    ];
                } else {
                    $down_pay   =   [
                        "metode_pembayaran" =>  ['required', Rule::exists('setup', 'id')->where('slug', 'payment')],
                        "down_payment"      =>  'required|numeric',
                    ];
                }
            }

            if ($request->check_supplier == 'on') {
                $cek_supplier =   [
                    'nama_supplier' =>  'required|string|max:100',
                    'nomor_telepon' =>  'required|numeric',
                    'alamat'        =>  'required|string'
                ];
            } else {
                $cek_supplier =   [
                    "supplier"          =>  'required|exists:supplier,id'
                ];
            }


            $validasi   =   Validator::make($request->all(), array_merge($cek_valid, $down_pay, $cek_supplier));

            if ($validasi->fails()) {
                return back()->withErrors($validasi)->withInput();
            }


            DB::beginTransaction();

            $produk                 =   new Purchase;
            $produk->nomor          =   Purchase::getnomor_purchase();

            if ($request->check_supplier == 'on') {

                $user               =   new User;
                $user->name         =   $request->nama_supplier;
                $user->type         =   'supplier';
                if (!$user->save()) {
                    DB::rollBack();
                }

                $supplier           =   new Supplier;
                $supplier->id       =   $user->id;
                $supplier->nama     =   $request->nama_supplier;
                $supplier->alamat   =   $request->alamat;
                $supplier->telepon  =   $request->nomor_telepon;
                if (!$supplier->save()) {
                    DB::rollBack();
                }

                $produk->supplier_id =   $user->id;
            } else {
                $produk->supplier_id =   $request->supplier;
            }

            // $produk->tipe           =   Produk::find($request->produk)->tipe;
            $produk->tanggal        =   $request->tanggal_purchase;
            $produk->user_id        =   Auth::user()->id;



            $item   =   [];
            $total  =   0;
            $tt_item =   0;
            for ($x = 0; $x < COUNT($request->produk); $x++) {
                if ($request->produk[$x]) {
                    $item[]   =   [
                        'id'        =>  rand(),
                        'produk'    =>  $request->produk[$x],
                        'harga'     =>  $request->harga_satuan[$x],
                        'jumlah'    =>  $request->jumlah_purchase[$x],
                        'terkirim'  =>  0,
                    ];
                    $total  +=  $request->harga_satuan[$x] * $request->jumlah_purchase[$x];
                    $tt_item    +=  $request->jumlah_purchase[$x];
                }
            }
            $produk->qty            =   $tt_item;
            $produk->total_harga    =   $total;
            $produk->produk         =   json_encode($item);

            $produk->tax            =   $request->tax;
            $produk->keterangan     =   $request->keterangan;
            $produk->down_payment   =   $request->down_payment;

            if ($request->check_kas) {
                $payment            =   new Setup;
                $payment->slug      =   'payment';
                $payment->nama      =   $request->tulis_pembayaran;
                $payment->status    =   2;
                if (!$payment->save()) {
                    DB::rollBack();
                }

                $produk->kas        =   $payment->id;
            } else {
                $produk->kas        =   $request->metode_pembayaran;
            }

            $produk->termin         =   $request->termin ?? 1;
            $start                  =   Carbon::parse($produk->tanggal);
            $produk->termin_tanggal =   $start->addDays($produk->termin);;
            $produk->status         =   1;
            if (!$produk->save()) {
                DB::rollBack();
            }

            if ($produk->down_payment) {
                $log                =   new LogTrans;
                $log->table         =   'purchase';
                $log->table_id      =   $produk->id;
                $log->produk_id     =   $produk->produk_id;
                $log->jenis         =   'dp';
                $log->tanggal       =   $request->tanggal_purchase;
                $log->kas           =   $produk->kas;
                $log->nominal       =   $produk->down_payment;
                $log->status        =   1;
                if (!$log->save()) {
                    DB::rollBack();
                }
            }

            DB::commit();

            return back()->with('status', 'Buat purchasing order berhasil');
        }
        return redirect()->route('home')->with('error', 'Anda tidak memiliki hak akses ke halaman tersebut');
    }

    public function update(Request $request)
    {
        if (User::setIjin('Purchasing Order')) {
            if ($request->down_payment) {
                $request->validate([
                    "item"              =>  ['required', Rule::exists('purchase', 'id')->where('status', 1)],
                    "produk"            =>  ['required', Rule::exists('produk', 'id')->where('jenis', 'purchase')],
                    "harga_satuan"      =>  'required|numeric',
                    "jumlah_purchase"   =>  'required|numeric',
                    "tanggal_purchase"  =>  'required|date',
                    "metode_pembayaran" =>  ['required', Rule::exists('setup', 'id')->where('slug', 'payment')],
                    "down_payment"      =>  'required',
                    "supplier"          =>  'required|exists:supplier,id'
                ]);
            } else {
                $request->validate([
                    "item"              =>  ['required', Rule::exists('purchase', 'id')->where('status', 1)],
                    "produk"            =>  ['required', Rule::exists('produk', 'id')->where('jenis', 'purchase')],
                    "harga_satuan"      =>  'required|numeric',
                    "jumlah_purchase"   =>  'required|numeric',
                    "tanggal_purchase"  =>  'required|date',
                    "supplier"          =>  'required|exists:supplier,id'
                ]);
            }

            DB::beginTransaction();

            $produk                 =   Purchase::find($request->item);
            $produk->supplier_id    =   $request->supplier;
            $produk->tipe           =   Produk::find($request->produk)->tipe;
            $produk->qty            =   $request->jumlah_purchase;
            $produk->total_harga    =   $request->harga_satuan * $request->jumlah_purchase;
            $produk->tanggal        =   $request->tanggal_purchase;
            $produk->user_id        =   Auth::user()->id;
            $produk->produk_id      =   $request->produk;
            $produk->tax            =   $request->tax;
            $produk->keterangan     =   $request->keterangan;
            $produk->down_payment   =   $request->down_payment ?? $produk->down_payment;
            $produk->kas            =   $request->metode_pembayaran ?? $produk->kas;
            $produk->termin         =   $request->termin;
            if (!$produk->save()) {
                DB::rollBack();
            }

            if ($request->down_payment) {
                $log                =   LogTrans::where('table_id', $produk->id)->where('table', 'purchase')->where('status', 1)->first();
                $log->kas           =   $request->metode_pembayaran;
                $log->tanggal       =   $request->tanggal_purchase;
                $log->nominal       =   $request->down_payment;
                if (!$log->save()) {
                    DB::rollBack();
                }
            }

            DB::commit();

            return back()->with('status', 'Ubah purchasing order berhasil');
        }
        return redirect()->route('home')->with('error', 'Anda tidak memiliki hak akses ke halaman tersebut');
    }

    public function pdf(Request $request)
    {
        if (User::setIjin('Purchasing Order')) {
            $request->validate([
                'mulai_report'  =>  'required|date',
                'akhir_report'  =>  'required|date|after:mulai_report'
            ]);

            $data   =   Purchase::orderByRaw('nomor ASC, tanggal DESC')
                ->whereBetween('tanggal', [$request->mulai_report, $request->akhir_report])
                ->get();

            $total  =   0;
            foreach ($data as $row) {
                $total  +=  $row->total_harga;
            }

            $resume =   [
                'jumlah'    =>  COUNT($data),
                'nominal'   =>  $total
            ];

            return view('transaksi.purchasing_order.excel', compact('request', 'data', 'resume'));

            // $pdf    =   App::make('dompdf.wrapper');
            // $pdf->getDomPDF()->set_option("enable_php", true);
            // $pdf->loadHTML(view('transaksi.purchasing_order.pdf', compact('request', 'data', 'resume')))->setPaper('A4', 'landscape');
            // return $pdf->stream();

            // $pdf    =   PDF::loadHTML(view('transaksi.purchasing_order.pdf', compact('request', 'data', 'resume')))->setPaper('A4', 'landscape');
            // $pdf->getDomPDF()->set_option("enable_php", true);
            // return $pdf->download('Summary Report Purchasing Order.pdf');
        }
        return redirect()->route('home')->with('error', 'Anda tidak memiliki hak akses ke halaman tersebut');
    }

    public function detailpdf($id)
    {
        if (User::setIjin('Purchasing Order')) {
            $data   =   Purchase::find($id);

            if ($data) {

                $ppn    =   $data->tax ? 0 : (($data->total_harga) * (10 / 100));

                // $pdf    =   App::make('dompdf.wrapper');
                // $pdf->loadHTML(view('transaksi.purchasing_order.detailpdf', compact('data', 'ppn')));
                // return $pdf->stream();

                $pdf    =   PDF::loadHTML(view('transaksi.purchasing_order.detailpdf', compact('data', 'ppn')));
                $pdf->getDomPDF()->set_option("enable_php", true);
                return $pdf->download('Purchasing Order ' . $data->nomor_purchasing . '.pdf');
            }
            return redirect()->route('index');
        }
        return redirect()->route('home')->with('error', 'Anda tidak memiliki hak akses ke halaman tersebut');
    }

    public function destroy(Request $request)
    {
        if (User::setIjin('Purchasing Order')) {
            if (Delivery::where('purchase_id', $request->x_code)->count() < 1) {
                $return =   Purchase::find($request->x_code)->delete();
            }

            return $return ? back()->with('status', 'Hapus purchasing order berhasil') : back()->with('error', 'Ubah satuan gagal');
        }
        return redirect()->route('home')->with('error', 'Anda tidak memiliki hak akses ke halaman tersebut');
    }
}

<?php

namespace App\Http\Controllers\Transaksi;

use App\Http\Controllers\Controller;
use App\Models\Auth\User;
use App\Models\Master\Produk;
use App\Models\Master\Setup;
use App\Models\Transaksi\LogTrans;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Tanggal ;

class Pembelian extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        if (User::setIjin('Jurnal Pembelian')) {
            if ($request->key == 'unduh') {
                if ($request->mulai || $request->selesai) {
                    $riwayat=   LogTrans::where('jenis', 'pembelian_lain')
                                ->orderByRaw('id DESC, tanggal DESC')
                                ->whereBetween('tanggal', [$request->mulai, $request->selesai])
                                ->get() ;

                    header("Content-type: application/csv");
                    header("Content-Disposition: attachment; filename=Pembelian Lain-Lain Periode " . Tanggal::date($request->mulai) . " - " . Tanggal::date($request->selesai) . ".csv");
                    $fp = fopen('php://output', 'w');
                    fputcsv($fp, ["sep=,"]);

                    $data   =   [
                        "No",
                        "Kandang",
                        "Tanggal",
                        "Produk",
                        "Satuan",
                        "Jumlah Pembelian",
                        "Harga Satuan",
                        "Total Transaksi",
                        "Metode Pembayaran",
                    ];
                    fputcsv($fp, $data);

                    foreach ($riwayat as $i => $row) {
                        $data   =   [
                            ++$i,
                            $row->kandang_id ? Setup::find($row->kandang_id)->nama : "-",
                            $row->tanggal,
                            $row->produk->nama,
                            $row->produk->tipesatuan->nama,
                            $row->qty,
                            $row->nominal / $row->qty,
                            $row->nominal,
                            $row->method->nama,
                        ];
                        fputcsv($fp, $data);
                    }

                    fclose($fp);
                    return '';
                }
                return back()->with('error', 'Pilih tanggal untuk unduh data');
            } else

            if ($request->key == 'input') {

                $payment=   Setup::where('slug', 'payment')
                            ->orderBy('nama', 'ASC')
                            ->pluck('nama', 'id');

                $produk =   Produk::where('supplier_id', NULL)
                            ->where('jenis', 'lain')
                            ->orderBy('nama', 'ASC')
                            ->get();

                $satuan =   Setup::where('slug', 'satuan')
                            ->orderBy('nama', 'ASC')
                            ->pluck('nama', 'id');

                $kandang=   Setup::where('slug', 'kandang')
                            ->orderBy('nama', 'ASC')
                            ->pluck('nama', 'id');

                return view('transaksi.pembelian.input', compact('payment', 'produk', 'satuan', 'kandang'));
            } else

            if ($request->key == 'daftar') {
                $q      =   $request->search ?? '' ;

                $riwayat=   LogTrans::where('jenis', 'pembelian_lain')
                            ->orderByRaw('id DESC, tanggal DESC')
                            ->get() ;

                $riwayat=   $riwayat->filter(function($item) use($q){
                    $res = true;
                    if ($q != "") {
                        $res =  (false !== stripos($item->produk->nama, $q)) ||
                                (false !== stripos($item->produk->tipesatuan->nama, $q)) ||
                                (false !== stripos(number_format($item->qty), $q)) ||
                                (false !== stripos($item->qty, $q)) ||
                                (false !== stripos(number_format($item->nominal), $q)) ||
                                (false !== stripos($item->nominal, $q)) ||
                                (false !== stripos(Tanggal::date($item->tanggal), $q)) ||
                                (false !== stripos($item->method->nama, $q));
                    }
                    return $res;
                });

                $riwayat    =   $riwayat->paginate(10) ;

                return view('transaksi.pembelian.daftar', compact('riwayat', 'q'));
            } else {
                return view('transaksi.pembelian.index');
            }
        }
        return redirect()->route('home')->with('error', 'Anda tidak memiliki hak akses ke halaman tersebut');
    }

    public function setup(Request $request)
    {
        if (User::setIjin('Jurnal Pembelian')) {
            if ($request->key == 'data') {
                $produk =   Produk::where('supplier_id', NULL)
                            ->where('jenis', 'lain')
                            ->orderBy('nama', 'ASC')
                            ->get();

                return view('transaksi.pembelian.setup_produk_data', compact('produk'));
            } else {
                return view('transaksi.pembelian.setup_produk');
            }

        }
        return redirect()->route('home')->with('error', 'Anda tidak memiliki hak akses ke halaman tersebut');
    }

    public function storesetup(Request $request)
    {
        $produk         =   Produk::find($request->id) ;
        $produk->tipe   =   $produk->tipe ? NULL : 1 ;
        $produk->save() ;
    }

    public function store(Request $request)
    {
        if (User::setIjin('Jurnal Pembelian')) {
            if (!$request->kandang) {
                $result['status']   =   400;
                $result['message']  =   "Kandang wajib dipilih";
                return $result;
            } else {
                if ($request->kandang != 'ALL') {
                    $kandang    =   Setup::where('slug', 'kandang')
                                    ->where('id', $request->kandang)
                                    ->first();

                    if (!$kandang) {
                        $result['status']   =   400;
                        $result['message']  =   "Kandang tidak ditemukan";
                        return $result;
                    }
                }
            }

            if ($request->check_produk == 'on') {
                if (!$request->tulis_produk) {
                    $result['status']   =   400 ;
                    $result['message']  =   "Produk wajib diisikan" ;
                    return $result ;
                }

                if ($request->check_satuan == 'on') {
                    if (!$request->tulis_satuan) {
                        $result['status']   =   400 ;
                        $result['message']  =   "Satuan wajib diisikan" ;
                        return $result ;
                    }
                } else {
                    $satuan     =   Setup::where('slug', 'satuan')
                                    ->where('id', $request->satuan)
                                    ->first();

                    if (!$satuan) {
                        $result['status']   =   400 ;
                        $result['message']  =   "Satuan tidak ditemukan" ;
                        return $result ;
                    }
                }

            } else {
                $produk =   Produk::where('id', $request->produk)
                            ->where('jenis', 'lain')
                            ->first() ;

                if (!$produk) {
                    $result['status']   =   400 ;
                    $result['message']  =   "Produk tidak ditemukan" ;
                    return $result ;
                }
            }

            if (!$request->jumlah_beli) {
                $result['status']   =   400 ;
                $result['message']  =   "Tuliskan jumlah pembelian" ;
                return $result ;
            }

            if (!$request->harga_pembelian) {
                $result['status']   =   400 ;
                $result['message']  =   "Harga satuan wajib diisikan" ;
                return $result ;
            }

            if (!$request->tanggal) {
                $result['status']   =   400 ;
                $result['message']  =   "Tanggal pembelian wajib diisikan" ;
                return $result ;
            }

            if ($request->check_kas == 'on') {
                if (!$request->tulis_pembayaran) {
                    $result['status']   =   400 ;
                    $result['message']  =   "Tuliskan metode pembayaran" ;
                    return $result ;
                }
            } else {
                $payment    =   Setup::where('slug', 'payment')
                                ->where('id', $request->metode_pembayaran)
                                ->first() ;

                if (!$payment) {
                    $result['status']   =   400 ;
                    $result['message']  =   "Metode pembayaran tidak ditemukan" ;
                    return $result ;
                }
            }

            DB::beginTransaction() ;

            $log                    =   new LogTrans ;

            if ($request->check_produk == 'on') {
                $produk         =   new Produk ;
                $produk->jenis  =   'lain' ;
                $produk->nama   =   $request->tulis_produk ;

                if ($request->check_satuan == 'on') {
                    $satuan         =   new Setup ;
                    $satuan->slug   =   'satuan' ;
                    $satuan->nama   =   $request->tulis_satuan ;
                    $satuan->status =   1 ;
                    if (!$satuan->save()) {
                        $result['status']   =   400 ;
                        $result['message']  =   "Proses gagal" ;
                        return $result ;
                    }

                    $produk->satuan =   $satuan->id ;
                } else {
                    $produk->satuan =   $request->satuan ;
                }

                if (!$produk->save()) {
                    $result['status']   =   400 ;
                    $result['message']  =   "Proses gagal" ;
                    return $result ;
                }

                $log->produk_id         =   $produk->id ;
            } else {
                $log->produk_id         =   $request->produk ;
            }

            $log->jenis                 =   "pembelian_lain" ;
            $log->kandang_id            =   $kandang->id ?? NULL ;
            $log->tanggal               =   $request->tanggal ;

            if ($request->check_kas == 'on') {
                $payment                =   new Setup;
                $payment->slug          =   'payment';
                $payment->nama          =   $request->tulis_pembayaran;
                $payment->status        =   2;
                if (!$payment->save()) {
                    $report['status']   =   400;
                    $report['message']  =   'Proses gagal';
                    return $report ;
                }

                $log->kas               =   $payment->id ;
            } else {
                $log->kas               =   $request->metode_pembayaran ;
            }

            $log->qty                   =   $request->jumlah_beli ;
            $log->nominal               =   $request->harga_pembelian * $request->jumlah_beli ;
            $log->status                =   1 ;

            if (!$log->save()) {
                DB::rollBack() ;
                $result['status']   =   400 ;
                $result['message']  =   "Proses gagal" ;
                return $result ;
            }

            DB::commit() ;
        }
        return redirect()->route('home')->with('error', 'Anda tidak memiliki hak akses ke halaman tersebut');
    }


    public function destroy(Request $request)
    {
        if (User::setIjin('Jurnal Pembelian')) {
            $trans  =   LogTrans::where('status', 1)
                        ->where('jenis', 'pembelian_lain')
                        ->where('id', $request->x_code)
                        ->first() ;

            if ($trans) {
                $trans->delete() ;
                return back()->with('status', 'Hapus pembelian berhasil') ;
            }

            return back()->with('error', 'Hapus pembelian gagal') ;
        }
        return redirect()->route('home')->with('error', 'Anda tidak memiliki hak akses ke halaman tersebut');
    }
}

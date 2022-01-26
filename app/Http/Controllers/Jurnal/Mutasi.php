<?php

namespace App\Http\Controllers\Jurnal;

use App\Http\Controllers\Controller;
use App\Models\Auth\User;
use App\Models\Jurnal\Angkatan;
use App\Models\Jurnal\RiwayatKandang;
use App\Models\Jurnal\StokKandang;
use App\Models\Master\Produk;
use App\Models\Master\Setup;
use App\Models\Master\Stok;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Tanggal;

class Mutasi extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        if (User::setIjin('Jurnal Mutasi')) {
            if ($request->key == 'unduh') {
                if ($request->mulai || $request->selesai) {
                    $stock  =   StokKandang::whereBetween('tanggal', [$request->mulai, $request->selesai])
                        ->orderBy('id', 'DESC')->where('jumlah', '>', 0)
                        ->get();

                    header("Content-type: application/csv");
                    header("Content-Disposition: attachment; filename=Data Mutasi Periode " . Tanggal::date($request->mulai) . " - " . Tanggal::date($request->selesai) . ".csv");
                    $fp = fopen('php://output', 'w');
                    fputcsv($fp, ["sep=,"]);


                    $data   =   [
                        "No",
                        "Tanggal",
                        "Jenis",
                        "Stock",
                        "Qty",
                        "Sisa",
                        "Kandang",
                    ];
                    fputcsv($fp, $data);

                    foreach ($stock as $i => $row) {
                        $data   =   [
                            ++$i,
                            $row->tanggal,
                            $row->produk->tipeset->nama,
                            $row->produk->nama,
                            $row->jumlah,
                            $row->sisa,
                            $row->kandang->nama,
                        ];
                        fputcsv($fp, $data);
                    }

                    fclose($fp);
                    return '';
                }
                return back()->with('error', "Pilih tanggal untuk unduh data");
            } else

            if ($request->key == 'riwayat') {
                $data   =   StokKandang::orderBy('id', 'DESC')->where('jumlah', '>', 0)->paginate(10);
                return view('jurnal.mutasi.riwayat', compact('data'));
            } else

            if ($request->key == 'produk') {
                $data   =   Produk::orderBy('tipe')
                    ->where('jenis', 'purchase')
                    ->where('tipe', '!=', 4)
                    ->get();

                return view('jurnal.mutasi.produk', compact('data'));
            } else

            if ($request->key == 'ayam') {
                $data   =   Produk::orderBy('tipe')
                    ->where('jenis', 'purchase')
                    ->where('tipe', 4)
                    ->get();

                return view('jurnal.mutasi.produk', compact('data'));
            } else

            if ($request->key == 'mutasi') {
                $data   =   Produk::orderBy('tipe')
                    ->where('jenis', 'purchase')
                    ->get();

                $kandang =   Setup::where('slug', 'kandang')
                    ->orderBy('nama', 'ASC')
                    ->get();

                return view('jurnal.mutasi.input', compact('data', 'kandang'));
            } else {
                return view('jurnal.mutasi.index');
            }
        }
        return redirect()->route('home')->with('error', 'Anda tidak memiliki hak akses ke halaman tersebut');
    }
    public function search_produk(Request $request){
      if ($request->key == "produk") {
        $data   =   Produk::orderBy('tipe')
        ->where('jenis', 'purchase')
        ->where('nama', 'like', '%'.$request->cari.'%')
        ->get();
      }
      elseif ($request->key == "riwayat") {
        $data   =   StokKandang::join('produk','produk.id','stock_kandang.produk_id')
        ->join('setup','setup.id','stock_kandang.kandang_id')
        ->orderBy('stock_kandang.id', 'DESC')->where('jumlah', '>', 0)
        ->where('produk.nama', 'like', '%'.$request->cari.'%')
        ->orWhere('setup.nama', 'like', '%'.$request->cari.'%')
        ->orWhere('stock_kandang.jumlah', 'like', '%'.$request->cari.'%')
        ->orWhere('stock_kandang.tanggal', 'like', '%'.$request->cari.'%')
        ->paginate(10);
        return view('jurnal.mutasi.riwayat', compact('data'));
      }

    return view('jurnal.mutasi.produk', compact('data'));
    }

    public function store(Request $request)
    {
        // return response()->json([
        //     'msg' => $request->all()
        // ]);
        if (User::setIjin('Jurnal Mutasi')) {
            $produk =   Produk::find($request->produk);

            if ($produk) {
                if ($request->qty > $produk->jumlah_stock) {
                    $data['status'] =   400;
                    $data['msg']    =   "Qty melebihi stock tersedia";
                    return $data;
                } else {
                    $kandang    =   Setup::where('slug', 'kandang')
                        ->where('id', $request->kandang)
                        ->first();

                    if ($kandang) {

                        $cek_kandang    =   RiwayatKandang::whereDate('tanggal', $request->tanggal_mutasi)
                            ->where('kandang', $kandang->id)
                            ->first();

                        if ($produk->tipe == 4) {
                            if ($kandang->status == 2) {

                                if (!$cek_kandang) {
                                    $data['status'] =   400;
                                    $data['msg']    =   "Kandang sudah digunakan";
                                    return $data;
                                }
                            }
                        }

                        DB::beginTransaction();

                        if ($produk->tipe == 4) {
                            $strain = Produk::where('id', $request->produk)->first()->strain;
                            // return response()->json(['strain' => $strain]);
                            $ang = Angkatan::orderBy('no', 'DESC')->first();
                            if (empty($ang)) {
                                $angkatan_no = 1;
                            } else {
                                $angkatan_no = Angkatan::orderBy('no', 'DESC')->first()->no + 1;
                            }
                            // return response()->json(['e' => $angkatan_no]);
                            $angkatan                       =   $cek_kandang ? Angkatan::find($cek_kandang->angkatan) : new Angkatan;
                            $angkatan->no                   =   $angkatan_no;
                            $angkatan->tanggal              =   $request->tanggal_mutasi;
                            $angkatan->populasi_awal        =   $cek_kandang ? ($angkatan->populasi_awal + $request->qty) : $request->qty;
                            $angkatan->user_id              =   Auth::user()->id;
                            $angkatan->status               =   1;

                            if (!$angkatan->save()) {
                                DB::rollBack();
                                $data['status'] =   400;
                                $data['msg']    =   "Proses Gagal";
                                return $data;
                            }

                            $riwayat                        =   $cek_kandang ?? new RiwayatKandang;
                            $riwayat->angkatan              =   $angkatan->id;
                            $riwayat->tanggal               =   $request->tanggal_mutasi;
                            $riwayat->strain_id             =   $strain;
                            $riwayat->kandang               =   $kandang->id;
                            $riwayat->populasi              =   $angkatan->populasi_awal;
                            $riwayat->status                =   1;
                            if (!$riwayat->save()) {
                                DB::rollBack();
                                $data['status'] =   400;
                                $data['msg']    =   "Proses Gagal";
                                return $data;
                            }

                            $kandang->status        =   2;
                            if (!$kandang->save()) {
                                DB::rollBack();
                                $data['status'] =   400;
                                $data['msg']    =   "Proses Gagal";
                                return $data;
                            }
                        }


                        $stock  =   Stok::where('produk_id', $produk->id)->get();

                        $sisa   =   $request->qty;
                        foreach ($stock as $row) {
                            if ($sisa > 0) {
                                if ($sisa >= $row->stock_opname) {
                                    $ambil  =   $row->stock_opname;
                                } else {
                                    $ambil  =   $sisa;
                                }
                                $sisa   =   ($sisa - $ambil);

                                $row->stock_opname  =   $row->stock_opname - $ambil;
                                if (!$row->save()) {
                                    DB::rollBack();
                                    $data['status'] =   400;
                                    $data['msg']    =   "Proses Gagal";
                                    return $data;
                                }

                                if ($produk->tipe != 4) {
                                    $stockkandang               =   new StokKandang;
                                    $stockkandang->user_id      =   Auth::user()->id;
                                    $stockkandang->stock_id     =   $row->id;
                                    $stockkandang->tanggal      =   $request->tanggal_mutasi;
                                    $stockkandang->produk_id    =   $produk->id;
                                    $stockkandang->tipe         =   $row->tipe;
                                    $stockkandang->jumlah       =   $ambil;
                                    $stockkandang->sisa         =   $ambil;
                                    $stockkandang->kandang_id   =   $kandang->id;
                                    if (!$stockkandang->save()) {
                                        DB::rollBack();
                                        $data['status'] =   400;
                                        $data['msg']    =   "Proses Gagal";
                                        return $data;
                                    }
                                }
                            }
                        }

                        DB::commit();
                    } else {
                        $data['status'] =   400;
                        $data['msg']    =   "Kandang tidak tersedia";
                        return $data;
                    }
                }
            } else {
                $data['status'] =   400;
                $data['msg']    =   "Produk belum dipilih";
                return $data;
            }
        }
        return redirect()->route('home')->with('error', 'Anda tidak memiliki hak akses ke halaman tersebut');
    }
}

<?php

namespace App\Http\Controllers\Transaksi;

use App\Http\Controllers\Controller;
use App\Models\Auth\User;
use App\Models\Jurnal\Populasi;
use App\Models\Jurnal\RiwayatKandang;
use App\Models\Master\CRM;
use App\Models\Master\Produk;
use App\Models\Master\Setup;
use App\Models\Transaksi\HeaderTrans;
use App\Models\Transaksi\ListTrans;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PDF;

class JualAyam extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        if (User::setIjin('Jurnal Penjualan Ayam')) {
            if ($request->key == 'stock') {
                $data   =   RiwayatKandang::where('status', 1)
                    ->orderBy('angkatan', 'ASC')
                    ->get();

                return view('transaksi.penjualan_ayam.stock', compact('data'));
            } else
            if ($request->key == 'list_trans') {
                $data   =   ListTrans::where('type', 'jual_ayam')
                    ->where('header_id', NULL)
                    ->orderBy('id', 'DESC')
                    ->get();

                return view('transaksi.penjualan_ayam.list', compact('data'));
            } else
            if ($request->key == 'daftar_trans') {
                $data   =   HeaderTrans::where('jenis', 'penjualan_ayam')
                    ->orderBy('id', 'DESC')
                    ->limit(5)
                    ->get();

                return view('transaksi.penjualan_ayam.daftar', compact('data'));
            } else
            if ($request->key == 'input_trans') {
                $konsumen   =   CRM::orderBy('nama_konsumen', 'ASC')->pluck('nama_konsumen', 'id');

                $payment    =   Setup::where('slug', 'payment')
                    ->pluck('nama', 'id');

                $transaksi  =   HeaderTrans::where('status', 2)
                    ->where('adj', NULL)
                    ->where('jenis', 'penjualan_ayam')
                    ->orderByRaw('id DESC, tanggal DESC')
                    ->get();

                return view('transaksi.penjualan_ayam.trans', compact('konsumen', 'payment', 'transaksi'));
            } else {

                return view('transaksi.penjualan_ayam.index');
            }
        }
        return redirect()->route('home')->with('error', 'Anda tidak memiliki hak akses ke halaman tersebut');
    }


    public function invoice($id)
    {
        if (User::setIjin('Jurnal Penjualan Ayam')) {
            $data   =   HeaderTrans::where('id', $id)
                ->where('jenis', 'penjualan_ayam')
                ->first();

            if ($data) {
                $pdf    =   App::make('dompdf.wrapper');
                $pdf->getDomPDF()->set_option("enable_php", true);
                $pdf->loadHTML(view('transaksi.penjualan_ayam.pdf', compact('data')));
                return $pdf->stream();

                $pdf    =   PDF::loadHTML(view('transaksi.penjualan_ayam.pdf', compact('data')));
                $pdf->getDomPDF()->set_option("enable_php", true);
                return $pdf->download('Summary Report Purchasing Order.pdf');
            } else {
                return redirect()->route('penjualan.index');
            }
        }
        return redirect()->route('home')->with('error', 'Anda tidak memiliki hak akses ke halaman tersebut');
    }


    public function store(Request $request)
    {
        // dd($request->all());
        if (User::setIjin('Jurnal Penjualan Ayam')) {
            $riwayat    =   RiwayatKandang::find($request->id);

            if ($riwayat) {
                if ($request->jual_ayam > 0) {
                    if ($request->harga_ayam > 0) {
                        if ($request->jual_ayam > $riwayat->populasi_akhir) {
                            $report['status']   =   400;
                            $report['message']  =   'Jumlah ayam melebihi stock tersedia';
                            return $report;
                        } else {
                            $list               =   new ListTrans;
                            $list->kandang_id   =   $riwayat->kandang;
                            $list->type         =   'jual_ayam';
                            $list->qty          =   $request->jual_ayam;
                            $list->kandang_id   =   $request->kandang;
                            $list->angkatan_id  =   $request->angkatan;
                            $list->stok_id      =   $riwayat->id;
                            $list->product_id   =   Produk::where('strain', $request->strain)->first()->id;
                            $list->total_harga  =   $request->harga_ayam;
                            $list->harga_satuan =   ($list->total_harga / $list->qty);
                            $list->save();

                            $report['status']   =   200;
                            $report['message']  =   'Sukses';
                            return $report;
                        }
                    } else {
                        $report['status']   =   400;
                        $report['message']  =   'Harga jual wajib diisikan';
                        return $report;
                    }
                } else {
                    $report['status']   =   400;
                    $report['message']  =   'Jumlah ayam wajib diisikan';
                    return $report;
                }
            } else {
                $report['status']   =   400;
                $report['message']  =   'Data tidak ditemukan';
                return $report;
            }
        }
        return redirect()->route('home')->with('error', 'Anda tidak memiliki hak akses ke halaman tersebut');
    }


    public function update(Request $request)
    {
        if (User::setIjin('Jurnal Penjualan Ayam')) {
            if ($request->check_kas == 'on') {
                if (!$request->tulis_pembayaran) {
                    $report['status']   =   400;
                    $report['message']  =   'Pembayaran tidak boleh kosong';
                    return $report;
                }
            } else {
                if (!$request->metode_pembayaran) {
                    $report['status']   =   400;
                    $report['message']  =   'Pembayaran tidak boleh kosong';
                    return $report;
                }
            }

            if ($request->check_konsumen == 'on') {
                if (!$request->nama_konsumen) {
                    $report['status']   =   400;
                    $report['message']  =   'Data konsumen wajib diisikan';
                    return $report;
                }
            } else {
                if (!CRM::find($request->konsumen)) {
                    $report['status']   =   400;
                    $report['message']  =   'Pilih konsumen';
                    return $report;
                }
            }

            if (!$request->tanggal) {
                $report['status']   =   400;
                $report['message']  =   'Pilih tanggal transaksi';
                return $report;
            }

            $dibayar    =   str_replace(',', '', $request->nominal_bayar);

            $total      =   ListTrans::where('type', 'jual_ayam')
                ->where('header_id', NULL)
                ->orderBy('id', 'DESC')
                ->sum('total_harga');

            if ($dibayar < $total) {
                $report['status']   =   400;
                $report['message']  =   'Nominal dibayarkan kurang';
                return $report;
            }

            DB::beginTransaction();

            $trans                  =   new HeaderTrans;
            $trans->jenis           =   'penjualan_ayam';
            $trans->nomor           =   HeaderTrans::ambil_nomor($trans->jenis);
            $trans->tanggal         =   $request->tanggal;
            $trans->user_id         =   Auth::user()->id;

            if ($request->check_konsumen == 'on') {
                $adduser                =   new User;
                $adduser->name          =   $request->nama_konsumen;
                $adduser->type          =   'konsumen';
                if (!$adduser->save()) {
                    DB::rollBack();
                    $report['status']   =   400;
                    $report['message']  =   'Proses gagal';
                    return $report;
                }

                $crm                =   new CRM;
                $crm->id            =   $adduser->id;
                $crm->nama_konsumen =   $request->nama_konsumen;
                $crm->telepon       =   $request->nomor_telepon;
                $crm->alamat        =   $request->alamat;
                if (!$crm->save()) {
                    DB::rollBack();
                    $report['status']   =   400;
                    $report['message']  =   'Proses gagal';
                    return $report;
                }

                $trans->konsumen_id     =   $crm->id;
                $trans->nama_konsumen   =   $request->nama_konsumen;
            } else {
                $trans->konsumen_id     =   $request->konsumen;
                $trans->nama_konsumen   =   CRM::find($trans->konsumen_id)->nama_konsumen;
            }

            $trans->total_trans     =   $total;
            $trans->payment         =   $dibayar;

            if ($request->check_kas == 'on') {
                $payment                =   new Setup;
                $payment->slug          =   'payment';
                $payment->nama          =   $request->tulis_pembayaran;
                $payment->status        =   2;
                if (!$payment->save()) {
                    $report['status']   =   400;
                    $report['message']  =   'Proses gagal';
                    return $report;
                }

                $trans->payment_method  =   $payment->id;
            } else {
                $trans->payment_method  =   $request->metode_pembayaran;
            }

            $trans->cashback        =   $trans->payment - $trans->total_trans;
            $trans->keterangan      =   $request->keterangan;
            $trans->status          =   1;
            if (!$trans->save()) {
                $report['status']   =   400;
                $report['message']  =   'Proses gagal';
                return $report;
            }


            if ($request->perubahan) {
                $adjust             =   HeaderTrans::find($request->perubahan);
                $adjust->adj        =   $trans->id;
                if (!$adjust->save()) {
                    $report['status']   =   400;
                    $report['message']  =   'Proses gagal';
                    return $report;
                }
            }

            $data_transaksi         =   ListTrans::where('type', 'jual_ayam')
                ->where('header_id', NULL)
                ->orderBy('id', 'DESC')
                ->get();

            foreach ($data_transaksi as $row) {
                $row->header_id     =   $trans->id;
                if (!$row->save()) {
                    $report['status']   =   400;
                    $report['message']  =   'Proses gagal';
                    return $report;
                }

                $kandang                    =   RiwayatKandang::find($row->stok_id);
                $hari                       =   date_diff(date_create($request->tanggal), date_create($kandang->tanggal));
                $record                     =   Populasi::where('hari', $hari->d)
                    ->where('riwayat_id', $kandang->id)
                    ->first() ?? new Populasi;

                $record->riwayat_id         =   $kandang->id;
                $record->kandang            =   $kandang->kandang;
                $record->tanggal_masuk      =   $kandang->tanggal;
                $record->hari               =   $hari->d + 1;
                $record->tanggal_input      =   $request->tanggal;
                $record->populasi_mati      =   $record->populasi_mati ?? 0;
                $record->populasi_afkir     =   $record->populasi_afkir ?? 0;
                $record->populasi_panen     =   $record->populasi_panen + $row->qty;
                if (!$record->save()) {
                    $report['status']   =   400;
                    $report['message']  =   'Proses gagal';
                    return $report;
                }


                if ($kandang->populasi_akhir == 0) {
                    $on_kandang         =   Setup::find($kandang->kandang);
                    $on_kandang->status =   1;
                    if (!$on_kandang->save()) {
                        $report['status']   =   400;
                        $report['message']  =   'Proses gagal';
                        return $report;
                    }

                    $kandang->status    =   2;
                    if (!$kandang->save()) {
                        $report['status']   =   400;
                        $report['message']  =   'Proses gagal';
                        return $report;
                    }
                }
            }

            return DB::commit();
        }
        return redirect()->route('home')->with('error', 'Anda tidak memiliki hak akses ke halaman tersebut');
    }


    public function destroy(Request $request)
    {
        if (User::setIjin('Jurnal Penjualan Ayam')) {
            $data   =   ListTrans::where('type', 'jual_ayam')
                ->where('id', $request->id)
                ->first();

            if ($data) {
                $data->delete();
            } else {
                $report['status']   =   400;
                $report['message']  =   'Data tidak ditemukan';
                return $report;
            }
        }
        return redirect()->route('home')->with('error', 'Anda tidak memiliki hak akses ke halaman tersebut');
    }


    public function hapus(Request $request)
    {
        if (User::setIjin('Jurnal Penjualan Ayam')) {
            $data   =   HeaderTrans::where('jenis', 'penjualan_ayam')
                ->where('id', $request->id)
                ->first();

            if ($data) {
                $data->status       =   2;
                $data->save();

                $list   =   ListTrans::where('header_id', $data->id)
                    ->get();

                foreach ($list as $row) {
                    $kandang            =   RiwayatKandang::find($row->stok_id);
                    $kandang->status    =   1;
                    $kandang->save();

                    $record                     =   Populasi::where('tanggal_input', $data->tanggal)
                        ->where('riwayat_id', $kandang->id)
                        ->first();

                    $record->populasi_panen     =   $record->populasi_panen - $row->qty;
                    $record->save();

                    // $on_kandang         =   Setup::find($kandang->kandang) ;
                    // $on_kandang->status =   1 ;
                    // $on_kandang->save();
                }

                $report['status']   =   200;
                $report['message']  =   'Sukses';
                return $report;
            } else {
                $report['status']   =   400;
                $report['message']  =   'Data tidak ditemukan';
                return $report;
            }
        }
        return redirect()->route('home')->with('error', 'Anda tidak memiliki hak akses ke halaman tersebut');
    }
}

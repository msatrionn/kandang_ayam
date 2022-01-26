<?php

namespace App\Http\Controllers\Transaksi;

use App\Http\Controllers\Controller;
use App\Models\Auth\User;
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

class JualLain extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        if (User::setIjin('Jurnal Penjualan Lain')) {
            if ($request->key == 'daftar_trans') {
                $data   =   HeaderTrans::where('jenis', 'penjualan_lain')
                    ->orderBy('id', 'DESC')
                    ->limit(5)
                    ->get();

                return view('transaksi.penjualan_lain.daftar', compact('data'));
            } else
            if ($request->key == 'form_trans') {
                $konsumen   =   CRM::orderBy('nama_konsumen', 'ASC')
                    ->pluck('nama_konsumen', 'id');

                $payment    =   Setup::where('slug', 'payment')
                    ->pluck('nama', 'id');

                $transaksi  =   HeaderTrans::where('status', 2)
                    ->where('adj', NULL)
                    ->where('jenis', 'penjualan_lain')
                    ->orderByRaw('id DESC, tanggal DESC')
                    ->get();

                return view('transaksi.penjualan_lain.trans', compact('konsumen', 'payment', 'transaksi'));
            } else
            if ($request->key == 'form_input') {
                $produk =   Produk::where('supplier_id', NULL)
                    ->where('jenis', NULL)
                    ->orderBy('nama', 'ASC')
                    ->get();

                $satuan =   Setup::where('slug', 'satuan')
                    ->orderBy('nama', 'ASC')
                    ->pluck('nama', 'id');

                $kandang =   Setup::where('slug', 'kandang')
                    ->orderBy('nama', 'ASC')
                    ->pluck('nama', 'id');

                return view('transaksi.penjualan_lain.form_input', compact('produk', 'satuan', 'kandang'));
            } else
            if ($request->key == 'daftar_list') {
                $value = $request->cari;
                $data   =   ListTrans::where('type', 'jual_lain')
                    // ->orwhere('harga_satuan', 'like', '%' . $value . '%')
                    ->whereHas('produk', function ($query) use ($value) {
                        $query->where('nama', 'like', '%' . $value . '%');
                    })
                    ->with(['produk' => function ($query) use ($value) {
                        $query->where('nama', 'like', '%' . $value . '%');
                    }])
                    ->orWhereBetween('created_at', [$request->tgl1, $request->tgl2])
                    ->orderBy('id', 'DESC')
                    ->paginate(5);
                $total   =   ListTrans::where('type', 'jual_lain')->where('header_id', NULL)->sum('total_harga');

                return view('transaksi.penjualan_lain.list', compact('data', 'total'));
            }
            if ($request->key == 'daftar_list_tgl') {
                $value = $request->cari;
                $data   =   ListTrans::where('type', 'jual_lain')
                    // ->orwhere('harga_satuan', 'like', '%' . $value . '%')
                    ->whereHas('produk', function ($query) use ($value) {
                        $query->where('nama', 'like', '%' . $value . '%');
                    })
                    ->with(['produk' => function ($query) use ($value) {
                        $query->where('nama', 'like', '%' . $value . '%');
                    }])
                    ->whereBetween('created_at', [$request->tgl1, $request->tgl2])
                    ->orderBy('id', 'DESC')
                    ->paginate(5);
                $total   =   ListTrans::where('type', 'jual_lain')->where('header_id', NULL)->sum('total_harga');

                return view('transaksi.penjualan_lain.list', compact('data', 'total'));
            } else {
                return view('transaksi.penjualan_lain.index');
            }
        }
        return redirect()->route('home')->with('error', 'Anda tidak memiliki hak akses ke halaman tersebut');
    }


    public function invoice($id)
    {
        if (User::setIjin('Jurnal Penjualan Lain')) {
            $data   =   HeaderTrans::where('id', $id)
                ->where('jenis', 'penjualan_lain')
                ->first();

            if ($data) {
                $pdf    =   App::make('dompdf.wrapper');
                $pdf->getDomPDF()->set_option("enable_php", true);
                $pdf->loadHTML(view('transaksi.penjualan_lain.pdf', compact('data')));
                return $pdf->stream();

                $pdf    =   PDF::loadHTML(view('transaksi.penjualan_lain.pdf', compact('data')));
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
        if (User::setIjin('Jurnal Penjualan Lain')) {
            for ($i = 0; $i < count($request->produk); $i++) {
                if (!$request->kandang[$i]) {
                    $result['status']   =   400;
                    $result['message']  =   "Kandang wajib dipilih";
                    return $result;
                } else {
                    if ($request->kandang[$i] != 'ALL') {
                        $kandang    =   Setup::where('slug', 'kandang')
                            ->where('id', $request->kandang[$i])
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
                        $result['status']   =   400;
                        $result['message']  =   "Produk wajib diisikan";
                        return $result;
                    }

                    if ($request->check_satuan == 'on') {
                        if (!$request->tulis_satuan) {
                            $result['status']   =   400;
                            $result['message']  =   "Satuan wajib diisikan";
                            return $result;
                        }
                    } else {
                        $cek_satuan =   Setup::where('slug', 'satuan')
                            ->where('id', $request->satuan[$i])
                            ->count();

                        if ($cek_satuan < 1) {
                            $result['status']   =   400;
                            $result['message']  =   "Satuan tidak ditemukan";
                            return $result;
                        }
                    }
                } else {
                    $cek_produk =   Produk::where('id', $request->produk[$i])
                        ->where('jenis', NULL)
                        ->where('supplier_id', '!=', NULL)
                        ->count();

                    if ($cek_produk) {
                        $result['status']   =   400;
                        $result['message']  =   "Produk tidak ditemukan";
                        return $result;
                    }
                }

                if (!$request->jumlah[$i]) {
                    $result['status']   =   400;
                    $result['message']  =   "Jumlah penjualan wajib diisikan";
                    return $result;
                }

                $nominal    =   str_replace(',', '', $request->nominal[$i]);

                if ($nominal < 1) {
                    $result['status']   =   400;
                    $result['message']  =   "Harga satuan wajib diisikan";
                    return $result;
                }

                DB::beginTransaction();

                $list               =   new ListTrans;
                $list->kandang_id   =   $kandang->id[$i] ?? NULL;
                $list->type         =   'jual_lain';
                $list->qty          =   $request->jumlah[$i];

                if ($request->check_produk[$i] ?? "" == 'on') {
                    $produk         =   new Produk;
                    $produk->nama   =   $request->tulis_produk[$i];

                    if ($request->check_satuan[$i] ?? "" == 'on') {
                        $satuan         =   new Setup;
                        $satuan->slug   =   'satuan';
                        $satuan->nama   =   $request->tulis_satuan[$i];
                        $satuan->status =   1;
                        if (!$satuan->save()) {
                            $result['status']   =   400;
                            $result['message']  =   "Proses gagal";
                            return $result;
                        }

                        $produk->satuan =   $satuan->id;
                    } else {
                        $produk->satuan =   $request->satuan[$i] ?? 0;
                    }

                    if (!$produk->save()) {
                        $result['status']   =   400;
                        $result['message']  =   "Proses gagal";
                        return $result;
                    }

                    $list->product_id   =   $produk->id;
                } else {
                    $list->product_id   =   $request->produk[$i];
                }

                $list->total_harga  =   $nominal * $request->jumlah[$i];
                $list->harga_satuan =   $request->nominal[$i];
                if (!$list->save()) {
                    $result['status']   =   400;
                    $result['message']  =   "Proses gagal";
                    return $result;
                }

                DB::commit();
            }
        }
        return redirect()->route('home')->with('error', 'Anda tidak memiliki hak akses ke halaman tersebut');
    }


    public function update(Request $request)
    {
        if (User::setIjin('Jurnal Penjualan Lain')) {
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

            $total      =   ListTrans::where('type', 'jual_lain')
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
            $trans->jenis           =   'penjualan_lain';
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
                    DB::rollBack();
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
                DB::rollBack();
                $report['status']   =   400;
                $report['message']  =   'Proses gagal';
                return $report;
            }


            if ($request->perubahan) {
                $adjust             =   HeaderTrans::find($request->perubahan);
                $adjust->adj        =   $trans->id;
                if (!$adjust->save()) {
                    DB::rollBack();
                    $report['status']   =   400;
                    $report['message']  =   'Proses gagal';
                    return $report;
                }
            }

            $data_transaksi         =   ListTrans::where('type', 'jual_lain')
                ->where('header_id', NULL)
                ->orderBy('id', 'DESC')
                ->get();

            foreach ($data_transaksi as $row) {
                $row->header_id     =   $trans->id;
                if (!$row->save()) {
                    DB::rollBack();
                    $report['status']   =   400;
                    $report['message']  =   'Proses gagal';
                    return $report;
                }
            }

            DB::commit();
        }
        return redirect()->route('home')->with('error', 'Anda tidak memiliki hak akses ke halaman tersebut');
    }


    public function destroy(Request $request)
    {
        if (User::setIjin('Jurnal Penjualan Lain')) {
            $data   =   ListTrans::where('type', 'jual_lain')
                ->where('id', $request->id)
                ->first();

            if ($data) {
                return $data->delete();
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
        if (User::setIjin('Jurnal Penjualan Lain')) {
            $data   =   HeaderTrans::where('jenis', 'penjualan_lain')
                ->where('id', $request->id)
                ->first();

            if ($data) {
                $data->status       =   2;
                $data->save();
            } else {
                $report['status']   =   400;
                $report['message']  =   'Data tidak ditemukan';
                return $report;
            }
        }
        return redirect()->route('home')->with('error', 'Anda tidak memiliki hak akses ke halaman tersebut');
    }
}

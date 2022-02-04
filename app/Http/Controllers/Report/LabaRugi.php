<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Models\Auth\User;
use App\Models\Jurnal\Angkatan;
use App\Models\Jurnal\RiwayatKandang;
use App\Models\Master\Stok;
use App\Models\Transaksi\Gaji;
use App\Models\Transaksi\HeaderTrans;
use Illuminate\Http\Request;

class LabaRugi extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {

        return view('report.labarugi.index');
    }
    public function table(Request $request)
    {
        if (User::setIjin('Report Laba Rugi')) {
            if (!empty($request->awal) or !empty($request->akhir)) {
                $penjualan_ayam     =   HeaderTrans::where('jenis', 'penjualan_ayam')
                    ->whereBetween('tanggal', [$request->awal, $request->akhir])
                    ->where('status', 1)
                    ->sum('total_trans');

                $penjualan_lain     =   HeaderTrans::select(['keterangan', 'total_trans'])->where('jenis', 'penjualan_lain')
                    ->whereBetween('tanggal', [$request->awal, $request->akhir])
                    ->where('status', 1)
                    ->get();

                $pengeluaran_lain     =   HeaderTrans::select(['keterangan', 'total_trans'])
                    ->where('jenis', 'pengeluaran_lain')
                    ->whereBetween('tanggal', [$request->awal, $request->akhir])
                    ->where('status', 1)
                    ->get();
                $penggajian     =   Gaji::whereBetween('tanggal', [$request->awal, $request->akhir])->sum('total_didapat');
            } elseif (!empty($request->angkatan)) {
                $penjualan_ayam     =   HeaderTrans::where('jenis', 'penjualan_ayam')
                    ->where('status', 1)
                    ->where('angkatan_id', $request->angkatan)
                    ->sum('total_trans');

                $penjualan_lain     =   HeaderTrans::select(['keterangan', 'total_trans'])->where('jenis', 'penjualan_lain')
                    ->where('status', 1)
                    ->where('angkatan_id', $request->angkatan)
                    ->get();

                $pengeluaran_lain     =   HeaderTrans::select(['keterangan', 'total_trans'])->where('jenis', 'pengeluaran_lain')
                    ->where('angkatan_id', $request->angkatan)
                    ->where('status', 1)
                    ->get();
                $penggajian     =   Gaji::whereBetween('tanggal', [$request->awal, $request->akhir])->sum('total_didapat');
            } else {
                $penjualan_ayam     =   HeaderTrans::where('jenis', 'penjualan_ayam')
                    ->where('status', 1)
                    ->sum('total_trans');

                $penjualan_lain     =   HeaderTrans::select(['keterangan', 'total_trans'])->where('jenis', 'penjualan_lain')
                    ->where('status', 1)
                    ->get();

                $pengeluaran_lain     =   HeaderTrans::select(['keterangan', 'total_trans'])->where('jenis', 'pengeluaran_lain')
                    ->where('status', 1)
                    ->get();
                $penggajian     =   Gaji::sum('total_didapat');
            }
            $stock              =   Stok::get();

            $hpp    =   0;

            $result =   [
                'penjualan_ayam'        =>  $penjualan_ayam,
                'penjualan_lain'        =>  $penjualan_lain,
                'pengeluaran_lain'        =>  $pengeluaran_lain,
                'penggajian_karyawan'        =>  $penggajian,
                'hpp'                   =>  $hpp,
            ];


            return view('report.labarugi.table', compact('result'));
        }
        return redirect()->route('home')->with('error', 'Anda tidak memiliki hak akses ke halaman tersebut');
    }
    public function angkatan(Request $request)
    {
        $angkatan = RiwayatKandang::select('angkatan')->whereBetween('tanggal', [$request->awal, $request->akhir])->distinct()->orderBy('angkatan', 'asc')->get();
        return view('report.labarugi.input_angkatan', compact('angkatan'));
    }
}

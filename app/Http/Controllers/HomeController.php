<?php

namespace App\Http\Controllers;

use App\Models\Jurnal\Aset;
use App\Models\Master\CRM;
use App\Models\Master\Produk;
use App\Models\Master\Setup;
use App\Models\Master\Supplier;
use App\Models\Transaksi\HeaderTrans;
use App\Models\Transaksi\ListTrans;
use App\Models\Transaksi\Purchase;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $jual_ayam  =   ListTrans::select(DB::raw("SUM(total_harga) AS total"), DB::raw('SUM(qty) AS jumlah'))
                        ->where('header_id', '!=', NULL)
                        ->where('type', 'jual_ayam')
                        ->whereIn('header_id', HeaderTrans::select('id')->whereYear('tanggal', date('Y'))->where('status', 1))
                        ->first();

        $jual_lain  =   ListTrans::select(DB::raw("SUM(total_harga) AS total"), DB::raw('SUM(qty) AS jumlah'))
                        ->where('header_id', '!=', NULL)
                        ->where('type', 'jual_lain')
                        ->whereIn('header_id', HeaderTrans::select('id')->whereYear('tanggal', date('Y'))->where('status', 1))
                        ->first();

        $tipe       =   Setup::where('slug', 'tipe')
                        ->orderBy('nama', 'ASC')
                        ->get() ;


        $list_trans =   [
            'penjualan_ayam'    =>  [
                'terjual'   =>  $jual_ayam->jumlah,
                'nominal'   =>  $jual_ayam->total,
            ],
            'penjualan_lain'    =>  [
                'terjual'   =>  $jual_lain->jumlah,
                'nominal'   =>  $jual_lain->total,
            ],
            'setoran_modal' =>  HeaderTrans::where('jenis', 'setor_modal')->whereYear('tanggal', date('Y'))->where('status', 1)->sum('total_trans'),
            'tarik_modal'   =>  HeaderTrans::where('jenis', 'tarik_modal')->whereYear('tanggal', date('Y'))->where('status', 1)->sum('total_trans'),
            'keluar_lain'   =>  HeaderTrans::where('jenis', 'pengeluaran_lain')->whereYear('tanggal', date('Y'))->where('status', 1)->sum('total_trans'),
            'tipe_produk'   =>  $tipe,
            'jatuh_tempo'   =>  Purchase::where('termin_tanggal', '<=', date('Y-m-d'))->where('status', 1)->get(),
        ];

        return view('home', compact('list_trans'));
    }

    public function aset()
    {
        $aset_tetap =   Aset::get();
        return view('jurnal.aset_tetap.home_aset', compact('aset_tetap'));
    }
}

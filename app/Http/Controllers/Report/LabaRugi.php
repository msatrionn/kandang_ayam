<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Models\Auth\User;
use App\Models\Master\Stok;
use App\Models\Transaksi\HeaderTrans;
use Illuminate\Http\Request;

class LabaRugi extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        if (User::setIjin('Report Laba Rugi')) {
            $penjualan_ayam     =   HeaderTrans::where('jenis', 'penjualan_ayam')
                                    ->where('status', 1)
                                    ->sum('total_trans');

            $penjualan_lain     =   HeaderTrans::where('jenis', 'penjualan_lain')
                                    ->where('status', 1)
                                    ->sum('total_trans');

            $stock              =   Stok::get();

            $hpp    =   0 ;

            $result =   [
                'penjualan_ayam'        =>  $penjualan_ayam ,
                'penjualan_lain'        =>  $penjualan_lain ,
                'hpp'                   =>  $hpp ,
            ];

            return view('report.labarugi.index', compact('result'));
        }
        return redirect()->route('home')->with('error', 'Anda tidak memiliki hak akses ke halaman tersebut');
    }
}

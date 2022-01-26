<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Master\Stok;
use Illuminate\Http\Request;
use Tanggal;

class StockBarang extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $q      =   $request->q ?? '' ;
        $data   =   Stok::select('stock.*', 'delivery.tanggal')
                    ->where('stock_opname', '>', 0)
                    ->leftJoin('delivery', 'delivery.id', '=', 'stock.delivery_id')
                    ->orderBy('tipe', 'ASC')
                    ->orderBy('delivery.tanggal', 'ASC')
                    ->get();

        $data   =   $data->filter(function ($item) use ($q) {
            $res = true;
            if ($q != "") {
                $res =  (false !== stripos($item->tipeset->nama, $q)) ||
                        (false !== stripos($item->produk->nama, $q)) ||
                        (false !== stripos(Tanggal::date($item->delivery->tanggal), $q)) ||
                        (false !== stripos($item->qty_awal, $q)) ||
                        (false !== stripos($item->qty_awal . ' ' . $item->produk->tipesatuan->nama, $q)) ||
                        (false !== stripos($item->stock_opname . ' ' . $item->produk->tipesatuan->nama, $q)) ||
                        (false !== stripos($item->stock_opname, $q));
            }
            return $res;
        });

        $data   =   $data->paginate(20);

        return view('master.stock.index', compact('data', 'q'));
    }
}

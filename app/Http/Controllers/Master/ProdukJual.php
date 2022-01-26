<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Master\Produk;
use App\Models\Master\Setup;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProdukJual extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $q      =   $request->q ?? '';
        $data   =   Produk::orderBy('nama', 'ASC')
                    ->where('jenis', NULL)
                    ->where('supplier_id', NULL)
                    ->get();

        $data   =   $data->filter(function($item) use($q){
                        $res = true;
                        if ($q != "") {
                            $res =  (false !== stripos($item->nama, $q)) ||
                                    (false !== stripos($item->tipeset->nama, $q)) ||
                                    (false !== stripos($item->tipesatuan->nama, $q));
                        }
                        return $res;
                    });

        $data   =   $data->paginate(10);

        $tipe   =   Setup::where('slug', 'tipe')->orderBy('nama', 'ASC')->pluck('nama', 'id');
        $satuan =   Setup::where('slug', 'satuan')->orderBy('nama', 'ASC')->pluck('nama', 'id');

        return view('master.produk_jual.index', compact('tipe', 'satuan', 'data', 'q'));
    }

    public function store(Request $request)
    {
        $request->validate([
            "nama_produk"       =>  'required|string',
            "tipe"              =>  ['required', Rule::exists('setup', 'id')->where('slug', 'tipe')],
            "satuan"            =>  ['required', Rule::exists('setup', 'id')->where('slug', 'satuan')],
        ]);

        $produk                 =   new Produk;
        $produk->tipe           =   $request->tipe;
        $produk->nama           =   $request->nama_produk;
        $produk->satuan         =   $request->satuan;
        $produk->save();

        return back()->with('status', 'Tambah produk berhasil');
    }
}

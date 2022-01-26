<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Auth\User;
use App\Models\Master\Setup;
use Illuminate\Http\Request;

class Satuan extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        if (User::setIjin('Daftar Satuan')) {
            $q      =   $request->q ?? '';
            $data   =   Setup::where('slug', 'satuan')
                        ->orderBy('nama', 'ASC')
                        ->get();

            $data   =   $data->filter(function($item) use($q){
                            $res = true;
                            if ($q != "") {
                                $res =  (false !== stripos($item->nama, $q));
                            }
                            return $res;
                        });

            $data   =   $data->paginate(30);

            return view('master.satuan.index', compact('data', 'q'));
        }
        return redirect()->route('home')->with('error', 'Anda tidak memiliki hak akses ke halaman tersebut');
    }

    public function store(Request $request)
    {
        if (User::setIjin('Daftar Satuan')) {
            $request->validate([
                'nama_satuan' =>  'required|string',
            ]);

            $satuan           =   new Setup;
            $satuan->slug     =   'satuan';
            $satuan->nama     =   $request->nama_satuan;
            $satuan->status   =   1;
            $satuan->save();

            return back()->with('status', 'Tambah satuan berhasil');
        }
        return redirect()->route('home')->with('error', 'Anda tidak memiliki hak akses ke halaman tersebut');
    }

    public function update(Request $request)
    {
        if (User::setIjin('Daftar Satuan')) {
            $satuan                   =   Setup::find($request->x_code);
            $satuan->nama             =   $request->nama_satuan;
            $satuan->save();

            if ($satuan) {
                return back()->with('status', 'Ubah satuan berhasil');
            } else {
                return back()->with('error', 'Ubah satuan gagal');
            }
        }
        return redirect()->route('home')->with('error', 'Anda tidak memiliki hak akses ke halaman tersebut');
    }

    public function destroy(Request $request)
    {
        if (User::setIjin('Daftar Satuan')) {
            $return =   Setup::find($request->x_code);
            if (COUNT($return->productsatuan) < 1) {
                $return->delete();
                return back()->with('status', 'Hapus satuan berhasil');
            }
            return back()->with('error', 'Ubah satuan gagal');
        }
        return redirect()->route('home')->with('error', 'Anda tidak memiliki hak akses ke halaman tersebut');
    }
}

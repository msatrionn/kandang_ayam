<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Auth\User;
use App\Models\Master\Setup;
use Illuminate\Http\Request;

class Tipe extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        if (User::setIjin('Daftar Tipe')) {
            $q      =   $request->q ?? '';
            $data   =   Setup::where('slug', 'tipe')
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

            return view('master.tipe.index', compact('data', 'q'));
        }
        return redirect()->route('home')->with('error', 'Anda tidak memiliki hak akses ke halaman tersebut');
    }

    public function store(Request $request)
    {
        if (User::setIjin('Daftar Tipe')) {
            $request->validate([
                'nama_tipe' =>  'required|string',
            ]);

            $tipe           =   new Setup ;
            $tipe->slug     =   'tipe' ;
            $tipe->nama     =   $request->nama_tipe ;
            $tipe->status   =   1 ;
            $tipe->save();

            return back()->with('status', 'Tambah tipe berhasil');
        }
        return redirect()->route('home')->with('error', 'Anda tidak memiliki hak akses ke halaman tersebut');
    }

    public function update(Request $request)
    {
        if (User::setIjin('Daftar Tipe')) {
            $tipe                   =   Setup::find($request->x_code) ;
            $tipe->nama             =   $request->nama_tipe;
            $tipe->save() ;

            if ($tipe) {
                return back()->with('status', 'Ubah tipe berhasil');
            } else {
                return back()->with('error', 'Ubah tipe gagal');
            }
        }
        return redirect()->route('home')->with('error', 'Anda tidak memiliki hak akses ke halaman tersebut');
    }

    public function destroy(Request $request)
    {
        if (User::setIjin('Daftar Tipe')) {
            $return =   Setup::find($request->x_code);
            if (COUNT($return->producttipe) < 1) {
                $return->delete();
                return back()->with('status', 'Hapus tipe berhasil');
            }
            return back()->with('error', 'Ubah tipe gagal');
        }
        return redirect()->route('home')->with('error', 'Anda tidak memiliki hak akses ke halaman tersebut');
    }
}

<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Auth\User;
use App\Models\Master\Setup;
use Illuminate\Http\Request;

class Kandang extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        if (User::setIjin('Daftar Kandang')) {
            $q      =   $request->q ?? '';
            $data   =   Setup::where('slug', 'kandang')
                        ->orderBy('nama', 'ASC')
                        ->get();

            $data   =   $data->filter(function ($item) use ($q) {
                            $res = true;
                            if ($q != "") {
                                $res =  (false !== stripos($item->nama, $q));
                            }
                            return $res;
                        });

            return view('master.kandang.index', compact('data', 'q'));
        }
        return redirect()->route('home')->with('error', 'Anda tidak memiliki hak akses ke halaman tersebut');
    }

    public function store(Request $request)
    {
        if (User::setIjin('Daftar Kandang')) {
            $request->validate([
                'nama_kandang'  =>  'required|string',
            ]);

            $kandang            =   new Setup;
            $kandang->slug      =   'kandang';
            $kandang->nama      =   $request->nama_kandang;
            // $kandang->json_data =   json_encode($array);
            $kandang->status    =   1;
            $kandang->save();

            return back()->with('status', 'Tambah kandang berhasil');
        }
        return redirect()->route('home')->with('error', 'Anda tidak memiliki hak akses ke halaman tersebut');
    }

    public function update(Request $request)
    {
        if (User::setIjin('Daftar Kandang')) {
            $kandang            =   Setup::find($request->x_code);
            $kandang->nama      =   $request->nama_kandang;
            $kandang->save();

            if ($kandang) {
                return back()->with('status', 'Ubah kandang berhasil');
            } else {
                return back()->with('error', 'Ubah kandang gagal');
            }
        }
        return redirect()->route('home')->with('error', 'Anda tidak memiliki hak akses ke halaman tersebut');
    }

    public function bangunan(Request $request)
    {
        if (User::setIjin('Daftar Kandang')) {
            $kandang        =   Setup::find($request->x_code);

            if ($kandang) {
                if (($request->bangunan) || ($request->kode) || ($request->jumlah_box) || ($request->ekor_per_box)) {
                    $bangunan =   json_decode($kandang->json_data, TRUE) ;

                    $array[]  =   [
                        'id'            =>  time(),
                        'bangunan'      =>  $request->bangunan ,
                        'kode'          =>  $request->kode ,
                        'jumlah_box'    =>  $request->jumlah_box ,
                        'ekor_per_box'  =>  $request->ekor_per_box
                    ] ;

                    $kandang->json_data =   json_encode($kandang->json_data ? array_merge($bangunan, $array) : $array);
                    $kandang->save();
                    return back()->with('status', 'Tambah bangunan kandang berhasil')->with('id', $kandang->id);
                }

            }
            return back()->with('error', 'Tambah bangunan kandang gagal')->with('id', $kandang->id);
        }
        return redirect()->route('home')->with('error', 'Anda tidak memiliki hak akses ke halaman tersebut');
    }

    public function destroy(Request $request)
    {
        if (User::setIjin('Daftar Kandang')) {
            $data   =   Setup::find($request->x_code);

            if ($data) {
                if ($request->type == 'master') {
                    $data->delete();
                    return back()->with('status', 'Hapus kandang berhasil');
                }

                if ($request->type == 'bangunan') {
                    $bangunan =   json_decode($data->json_data) ;

                    $array  =   [] ;
                    foreach ($bangunan as $row) {
                        if ($request->bg != $row->id) {
                            $array[]    =   [
                                'id'            =>  $row->id ,
                                'bangunan'      =>  $row->bangunan ,
                                'kode'          =>  $row->kode ,
                                'jumlah_box'    =>  $row->jumlah_box ,
                                'ekor_per_box'  =>  $row->ekor_per_box
                            ] ;
                        }
                    }
                    $data->json_data    =   json_encode($array) ;
                    $data->save() ;
                    return back()->with('status', 'Hapus bangunan berhasil')->with('id', $data->id);
                }
            }

            return back() ;
        }
        return redirect()->route('home')->with('error', 'Anda tidak memiliki hak akses ke halaman tersebut');
    }
}

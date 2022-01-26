<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Auth\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\Master\Strain as ModelStrain ;
use App\Models\Master\Setup;

class Strain extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        if (User::setIjin('Daftar Strain')) {
            if ($request->key == 'show') {
                $tab    =   $request->tab ?? '' ;
                $data   =   Setup::where('slug', 'strain')->get();
                return view('master.strain.show', compact('data', 'tab'));
            } else {
                return view('master.strain.index');
            }
        }
        return redirect()->route('home')->with('error', 'Anda tidak memiliki hak akses ke halaman tersebut');
    }

    public function store(Request $request)
    {
        if (User::setIjin('Daftar Strain')) {
            if ($request->key == 'standar') {
                $request->validate([
                    'nama_strain'   =>  'required|in:gram,global,bb',
                    'minggu_umur'   =>  'required|numeric',
                    'mulai_umur'    =>  'required|numeric',
                    'sampai_umur'   =>  'required|numeric',
                    'standar'       =>  'required',
                    'row_id'        =>  ['required', Rule::exists('setup', 'id')->where('slug', 'strain')],
                ]);

                $data               =   new ModelStrain ;
                $data->strain_id    =   $request->row_id ;
                $data->category     =   $request->nama_strain ;
                $data->minggu       =   $request->minggu_umur ;
                $data->dari         =   $request->mulai_umur ;
                $data->sampai       =   $request->sampai_umur ;
                $data->angka        =   $request->standar ;
                $data->save() ;
            } else {
                $request->validate([
                    'nama_strain'   =>  'required',
                ]);

                $strain             =   new Setup ;
                $strain->slug       =   'strain' ;
                $strain->nama       =   $request->nama_strain ;
                $strain->status     =   1 ;
                $strain->save() ;

                return back()->with('status', 'Tambah strain berhasil');
            }
        }
        return redirect()->route('home')->with('error', 'Anda tidak memiliki hak akses ke halaman tersebut');
    }

    public function update(Request $request)
    {
        if (User::setIjin('Daftar Strain')) {
            $request->validate([
                'nama_strain'   =>  'required',
            ]);

            $strain             =   Setup::find($request->row_id) ;
            $strain->nama       =   $request->nama_strain ;
            $strain->save() ;
        }
        return redirect()->route('home')->with('error', 'Anda tidak memiliki hak akses ke halaman tersebut');
    }

    public function standar(Request $request)
    {
        if (User::setIjin('Daftar Strain')) {
            $data   =   ModelStrain::where('strain_id', $request->strain)
                        ->where('id', $request->row_id)
                        ->first() ;

            if ($data) {
                $data->minggu       =   $request->minggu ;
                $data->dari         =   $request->dari ;
                $data->sampai       =   $request->sampai ;
                $data->angka        =   $request->angka ;
                $data->save() ;
            } else {
                $result['status']   =   400 ;
                $result['msg']      =   "Data tidak ditemukan" ;
                return $result ;
            }
        }
        return redirect()->route('home')->with('error', 'Anda tidak memiliki hak akses ke halaman tersebut');
    }

    public function destroy(Request $request)
    {
        if (User::setIjin('Daftar Strain')) {
            $request->validate([
                'row_id'    =>  ['required', Rule::exists('strain', 'id')->where('strain_id', $request->strain)]
            ]);

            return ModelStrain::find($request->row_id)->delete() ;
        }
        return redirect()->route('home')->with('error', 'Anda tidak memiliki hak akses ke halaman tersebut');
    }
}

<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Auth\User;
use App\Models\Master\Setup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class HakAkses extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        if (Auth::user()->type == 'admin') {
            $data   =   User::where('type', 'user')
                        ->paginate(10) ;

            $akses  =   Setup::where('slug', 'permission')
                        ->get() ;

            return view('master.hak_akses.index', compact('data', 'akses')) ;
        }
        return redirect()->route('home')->with('error', 'Anda tidak memiliki hak akses ke halaman tersebut');
    }

    public function store(Request $request)
    {
        if (Auth::user()->type == 'admin') {
            $user               =   new User ;
            $user->name         =   $request->nama_user ;
            $user->email        =   $request->email_user ;
            $user->password     =   Hash::make($request->password) ;
            $user->type         =   'user' ;
            $user->save() ;

            return back()->with('status', 'Tambah user berhasil') ;
        }
        return redirect()->route('home')->with('error', 'Anda tidak memiliki hak akses ke halaman tersebut');
    }

    public function update(Request $request)
    {
        if (Auth::user()->type == 'admin') {
            $user               =   User::find($request->id);
            $user->name         =   $request->nama_user;
            $user->email        =   $request->email_user;
            $user->password     =   $request->password ? Hash::make($request->password) : $user->password ;
            $user->permission   =   $request->chk ? collect($request->chk)->implode(',') : NULL;
            $user->save();

            return back()->with('status', 'Ubah user berhasil');
        }
        return redirect()->route('home')->with('error', 'Anda tidak memiliki hak akses ke halaman tersebut');
    }

    public function destroy(Request $request)
    {
        if (Auth::user()->type == 'admin') {
            $user   =   User::where('id', $request->id)
                        ->where('type', 'user')
                        ->first() ;

            if ($user) {
                $user->delete() ;
                return back()->with('status', 'User berhasil dihapus') ;
            } else {
                return back()->with('error', 'User tidak ditemukan') ;
            }
        }
        return redirect()->route('home')->with('error', 'Anda tidak memiliki hak akses ke halaman tersebut');
    }
}

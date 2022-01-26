<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Auth\User;
use App\Models\Master\Karyawan as MasterKaryawan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class Karyawan extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        if (User::setIjin('Daftar Karyawan')) {
            $q      =   $request->q ?? '';
            $data   =   MasterKaryawan::orderBy('nama', 'ASC')
                ->get();

            $data   =   $data->filter(function ($item) use ($q) {
                $res = true;
                if ($q != "") {
                    $res =  (false !== stripos($item->nama, $q)) ||
                        (false !== stripos($item->alamat, $q)) ||
                        (false !== stripos($item->gaji_harian, $q)) ||
                        (false !== stripos(number_format($item->gaji_harian), $q)) ||
                        (false !== stripos($item->telepon, $q));
                }
                return $res;
            });

            $data   =   $data->paginate(10);

            return view('master.karyawan.index', compact('data', 'q'));
        }
        return redirect()->route('home')->with('error', 'Anda tidak memiliki hak akses ke halaman tersebut');
    }

    public function store(Request $request)
    {
        // dd($request->all());
        if (User::setIjin('Daftar Karyawan')) {

            if (!empty($request->false) && $request->false = "on") {
                $request->validate([
                    'nama_karyawan' =>  'required',
                    'tanggal_masuk' =>  'required',
                    'gaji_per_hari' =>  'required',
                ]);
            } else {
                $request->validate([
                    'nama_karyawan' =>  'required',
                    'tanggal_masuk' =>  'required',
                    'gaji_per_hari' =>  'required',
                    'uang_makan' =>  'required',
                ]);
            }

            DB::beginTransaction();

            $adduser                =   new User;
            $adduser->name          =   $request->nama_karyawan;
            $adduser->type          =   'karyawan';
            if (!$adduser->save()) {
                DB::rollBack();
                return back()->with('error', 'Proses gagal. Silahkan ulangi kembali');
            }

            $karyawan               =   new MasterKaryawan;
            $karyawan->id           =   $adduser->id;
            $karyawan->nama         =   $adduser->name;
            $karyawan->alamat       =   $request->alamat ?? NULL;
            $karyawan->telepon      =   $request->nomor_telepon ?? NULL;
            $karyawan->tanggal_masuk =   $request->tanggal_masuk;
            $karyawan->gaji_harian  =   $request->gaji_per_hari;
            $karyawan->uang_makan =   $request->uang_makan;
            if (!$karyawan->save()) {
                DB::rollBack();
                return back()->with('error', 'Proses gagal. Silahkan ulangi kembali');
            }

            DB::commit();

            return back()->with('status', 'Tambah karyawan berhasil');
        }
        return redirect()->route('home')->with('error', 'Anda tidak memiliki hak akses ke halaman tersebut');
    }

    public function update(Request $request)
    {

        if (User::setIjin('Daftar Karyawan')) {
            $request->validate([
                'x_code'        =>  ['required', Rule::exists('users', 'id')->where('type', 'karyawan')],
                'nama_karyawan' =>  'required',
                'tanggal_masuk' =>  'required',
                'gaji_per_hari' =>  'required',
            ]);

            DB::beginTransaction();

            $adduser                =   User::find($request->x_code);
            $adduser->name          =   $request->nama_karyawan;
            $adduser->type          =   'karyawan';
            if (!$adduser->save()) {
                DB::rollBack();
                return back()->with('error', 'Proses gagal. Silahkan ulangi kembali');
            }

            $karyawan               =   MasterKaryawan::where('id', $adduser->id)->first();
            $karyawan->id           =   $adduser->id;
            $karyawan->nama         =   $adduser->name;
            $karyawan->alamat       =   $request->alamat ?? NULL;
            $karyawan->telepon      =   $request->nomor_telepon ?? NULL;
            $karyawan->tanggal_masuk =   $request->tanggal_masuk;
            $karyawan->gaji_harian  =   $request->gaji_per_hari;
            $karyawan->uang_makan  =   $request->uang_makan;
            if (!$karyawan->save()) {
                DB::rollBack();
                return back()->with('error', 'Proses gagal. Silahkan ulangi kembali');
            }

            DB::commit();

            return back()->with('status', 'Ubah data karyawan berhasil');
        }
        return redirect()->route('home')->with('error', 'Anda tidak memiliki hak akses ke halaman tersebut');
    }


    public function destroy(Request $request)
    {
        if (User::setIjin('Daftar Karyawan')) {
            $data   =   User::where('id', $request->x_code)->first();
            if ($data) {
                MasterKaryawan::where('id', $data->id)->delete();
                $data->delete();
                return back()->with('status', 'Hapus data karyawan berhasil');
            }
        }
        return redirect()->route('home')->with('error', 'Anda tidak memiliki hak akses ke halaman tersebut');
    }
}

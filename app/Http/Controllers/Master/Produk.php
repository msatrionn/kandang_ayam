<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Master\Produk as MasterProduk;
use App\Models\Master\Setup;
use App\Models\Master\Supplier;
use App\Models\Auth\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class Produk extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        if (User::setIjin('Daftar Produk')) {
            $q      =   $request->q ?? '';
            $data   =   MasterProduk::orderBy('nama', 'ASC')
                ->where('jenis', 'purchase')
                ->get();

            $data   =   $data->filter(function ($item) use ($q) {
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
            $strain =   Setup::where('slug', 'strain')->orderBy('nama', 'ASC')->pluck('nama', 'id');

            return view('master.produk.index', compact('data', 'tipe', 'satuan', 'q', 'strain'));
        }
        return redirect()->route('home')->with('error', 'Anda tidak memiliki hak akses ke halaman tersebut');
    }

    public function store(Request $request)
    {
        if (User::setIjin('Daftar Produk')) {
            $cek_valid  =   [
                "nama_produk"       =>  'required|string',
                "limit_stock"       =>  'required|numeric|min:0'
            ];

            if ($request->check_tipe == 'on') {
                $cek_tipe   =   [
                    "tipe_produk"   =>  'required|string'
                ];
            } else {
                $cek_tipe   =   [
                    "tipe"          =>  ['required', Rule::exists('setup', 'id')->where('slug', 'tipe')],
                ];
            }

            if ($request->check_satuan == 'on') {
                $cek_satuan =   [
                    "tulis_satuan"  =>  'required|string'
                ];
            } else {
                $cek_satuan =   [
                    "satuan"        =>  ['required', Rule::exists('setup', 'id')->where('slug', 'satuan')],
                ];
            }

            // if ($request->check_supplier == 'on') {
            //     $cek_supplier=   [
            //         'nama_supplier' =>  'required|string|max:100',
            //         'nomor_telepon' =>  'required|numeric',
            //         'alamat'        =>  'required|string'
            //     ];
            // } else {
            //     $cek_supplier=   [
            //         "supplier"          =>  'required|exists:supplier,id'
            //     ];
            // }

            $validasi   =   Validator::make($request->all(), array_merge($cek_valid, $cek_tipe, $cek_satuan));

            if ($validasi->fails()) {
                return back()->withErrors($validasi)->withInput();
            }

            DB::beginTransaction();

            $produk                 =   new MasterProduk;

            // if ($request->check_supplier == 'on') {

            //     $user               =   new User ;
            //     $user->name         =   $request->nama_supplier;
            //     $user->type         =   'supplier';
            //     if (!$user->save()) {
            //         DB::rollBack();
            //     }

            //     $supplier           =   new Supplier ;
            //     $supplier->id       =   $user->id ;
            //     $supplier->nama     =   $request->nama_supplier ;
            //     $supplier->alamat   =   $request->alamat ;
            //     $supplier->telepon  =   $request->nomor_telepon ;
            //     if (!$supplier->save()) {
            //         DB::rollBack();
            //     }

            //     $produk->supplier_id=   $user->id ;
            // } else {
            //     $produk->supplier_id=   $request->supplier ;
            // }

            if ($request->check_tipe == 'on') {
                $tipe           =   new Setup;
                $tipe->slug     =   'tipe';
                $tipe->nama     =   $request->tipe_produk;
                $tipe->status   =   1;
                if (!$tipe->save()) {
                    DB::rollBack();
                }
                $produk->tipe       =   $tipe->id;
            } else {
                $produk->tipe       =   $request->tipe;
            }

            $produk->jenis          =   'purchase';
            $produk->strain       =   $request->strain;
            $produk->nama           =   $request->nama_produk;
            $produk->stocklimit     =   $request->limit_stock ?? NULL;

            if ($request->check_satuan == 'on') {

                $satuan           =   new Setup;
                $satuan->slug     =   'satuan';
                $satuan->nama     =   $request->tulis_satuan;
                $satuan->status   =   1;
                if (!$satuan->save()) {
                    DB::rollBack();
                }

                $produk->satuan     =   $satuan->id;
            } else {
                $produk->satuan     =   $request->satuan;
            }

            if (!$produk->save()) {
                DB::rollBack();
            }

            DB::commit();

            return back()->with('status', 'Tambah produk berhasil');
        }
        return redirect()->route('home')->with('error', 'Anda tidak memiliki hak akses ke halaman tersebut');
    }

    public function update(Request $request)
    {
        if (User::setIjin('Daftar Produk')) {
            $request->validate([
                "nama_produk"       =>  'required|string',
                "tipe"              =>  ['required', Rule::exists('setup', 'id')->where('slug', 'tipe')],
                "satuan"            =>  ['required', Rule::exists('setup', 'id')->where('slug', 'satuan')]
            ]);

            $produk                 =   MasterProduk::find($request->x_code);
            $produk->tipe           =   $request->tipe;
            $produk->nama           =   $request->nama_produk;
            $produk->stocklimit     =   $request->limit_stock ?? NULL;
            $produk->satuan         =   $request->satuan;
            $produk->save();

            if ($produk) {
                return back()->with('status', 'Ubah produk berhasil');
            } else {
                return back()->with('error', 'Ubah produk gagal');
            }
        }
        return redirect()->route('home')->with('error', 'Anda tidak memiliki hak akses ke halaman tersebut');
    }

    public function destroy(Request $request)
    {
        if (User::setIjin('Daftar Produk')) {
            $return =   MasterProduk::find($request->x_code);
            if (COUNT($return->relatepurc) < 1) {
                $return->delete();
                return back()->with('status', 'Hapus produk berhasil');
            }
            return back()->with('error', 'Ubah produk gagal');
        }
        return redirect()->route('home')->with('error', 'Anda tidak memiliki hak akses ke halaman tersebut');
    }
}

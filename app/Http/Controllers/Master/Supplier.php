<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Auth\User;
use App\Models\Master\Supplier as MasterSupplier;
use App\Models\Transaksi\Purchase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use PDF;

class Supplier extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        if (User::setIjin('Daftar Supplier')) {
            $q      =   $request->q ?? '';
            $data   =   MasterSupplier::orderBy('nama', 'ASC')->get();

            $data   =   $data->filter(function($item) use($q){
                            $res = true;
                            if ($q != "") {
                                $res =  (false !== stripos($item->nama, $q)) ||
                                        (false !== stripos($item->alamat, $q)) ||
                                        (false !== stripos($item->telepon, $q));
                            }
                            return $res;
                        });

            $data   =   $data->paginate(10);

            return view('master.supplier.index', compact('data', 'q'));
        }
        return redirect()->route('home')->with('error', 'Anda tidak memiliki hak akses ke halaman tersebut');
    }

    public function store(Request $request)
    {
        if (User::setIjin('Daftar Supplier')) {
            $request->validate([
                'nama_supplier' =>  'required|string|max:100',
                'nomor_telepon' =>  'required|numeric',
                'alamat'        =>  'required|string'
            ]);

            DB::beginTransaction();

            $user                   =   new User ;
            $user->name             =   $request->nama_supplier;
            $user->type             =   'supplier';
            if (!$user->save()) {
                DB::rollBack();
            }

            $supplier               =   new MasterSupplier ;
            $supplier->id           =   $user->id ;
            $supplier->nama         =   $request->nama_supplier ;
            $supplier->alamat       =   $request->alamat ;
            $supplier->telepon      =   $request->nomor_telepon ;
            if (!$supplier->save()) {
                DB::rollBack();
            }

            DB::commit();

            return back()->with('status', 'Tambah supplier berhasil');
        }
        return redirect()->route('home')->with('error', 'Anda tidak memiliki hak akses ke halaman tersebut');
    }

    public function update(Request $request)
    {
        if (User::setIjin('Daftar Supplier')) {
            $request->validate([
                'nama_supplier' =>  'required|string|max:100',
                'nomor_telepon' =>  'required|numeric',
                'alamat'        =>  'required|string'
            ]);

            DB::beginTransaction();

            $supplier               =   MasterSupplier::find($request->x_code);
            $supplier->nama         =   $request->nama_supplier;
            $supplier->alamat       =   $request->alamat;
            $supplier->telepon      =   $request->nomor_telepon;
            if (!$supplier->save()) {
                DB::rollBack();
            }

            $user                   =   User::find($request->x_code);
            $user->name             =   $request->nama_supplier;
            if (!$user->save()) {
                DB::rollBack();
            }

            if ($user) {
                DB::commit();
                return back()->with('status', 'Ubah supplier berhasil');
            } else {
                return back()->with('error', 'Ubah supplier gagal');
            }
        }
        return redirect()->route('home')->with('error', 'Anda tidak memiliki hak akses ke halaman tersebut');
    }

    public function detail(Request $request)
    {
        if (User::setIjin('Daftar Supplier')) {
            $data   =   MasterSupplier::find($request->x_code);

            if ($data) {

                $purchase   =   Purchase::where('supplier_id', $data->id)
                                ->get();

                // $pdf    =   App::make('dompdf.wrapper');
                // $pdf->getDomPDF()->set_option("enable_php", true);
                // $pdf->loadHTML(view('master.supplier.pdf', compact('data', 'purchase')))->setPaper('A4', 'landscape');
                // return $pdf->stream();

                $pdf    =   PDF::loadHTML(view('master.supplier.pdf', compact('data', 'purchase')))->setPaper('A4', 'landscape');
                $pdf->getDomPDF()->set_option("enable_php", true);
                return $pdf->download('Laporan Supplier ' . $data->nama . '.pdf');
            }

            return redirect()->route('supplier.index');
        }
        return redirect()->route('home')->with('error', 'Anda tidak memiliki hak akses ke halaman tersebut');
    }

    public function destroy(Request $request)
    {
        if (User::setIjin('Daftar Supplier')) {
            $return =   MasterSupplier::find($request->x_code)->delete();
            return $return ? back()->with('status', 'Hapus supplier berhasil') : back()->with('error', 'Ubah supplier gagal') ;
        }
        return redirect()->route('home')->with('error', 'Anda tidak memiliki hak akses ke halaman tersebut');
    }
}

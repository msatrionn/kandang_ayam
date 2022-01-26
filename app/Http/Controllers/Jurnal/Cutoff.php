<?php

namespace App\Http\Controllers\Jurnal;

use App\Http\Controllers\Controller;
use App\Models\Auth\User;
use App\Models\Master\Produk;
use App\Models\Master\Stok;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Cutoff extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        if (User::setIjin('Jurnal Cut Off')) {
            if ($request->key == 'input') {
                $produk =   Produk::orderBy('nama', 'ASC')
                            ->where('jenis', 'purchase')
                            ->get();

                return view('jurnal.cutoff.input', compact('produk'));
            } else
            if ($request->key == 'data_cutoff') {
                $data   =   Stok::where('delivery_id', NULL)
                            ->orderBy('id', 'DESC')
                            ->paginate(10) ;

                return view('jurnal.cutoff.data', compact('data'));
            } else {
                return view('jurnal.cutoff.index');
            }
        }
        return redirect()->route('home')->with('error', 'Anda tidak memiliki hak akses ke halaman tersebut');
    }


    public function store(Request $request)
    {
        if (User::setIjin('Jurnal Cut Off')) {
            $item   =   Produk::where('id', $request->item)
                        ->where('jenis', 'purchase')
                        ->first() ;

            if (!$item) {
                $result['status']   =   400 ;
                $result['msg']      =   "Item produk wajib dipilih" ;
                return $result ;
            }

            if (!$request->tanggal) {
                $result['status']   =   400 ;
                $result['msg']      =   "Tanggal wajib diisikan" ;
                return $result ;
            }

            if (!$request->jumlah) {
                $result['status']   =   400 ;
                $result['msg']      =   "Jumlah wajib diisikan" ;
                return $result ;
            }

            DB::beginTransaction() ;

            $stock                  =   new Stok ;
            $stock->produk_id       =   $item->id ;
            $stock->qty_awal        =   $request->jumlah ;
            $stock->stock_opname    =   $request->jumlah ;
            $stock->tanggal         =   $request->tanggal ;
            $stock->tipe            =   $item->tipe ;
            if (!$stock->save()) {
                DB::rollBack();
                $result['status']   =   400;
                $result['msg']      =   "Proses Gagal";
                return $result;
            }

            DB::commit() ;
        }
        return redirect()->route('home')->with('error', 'Anda tidak memiliki hak akses ke halaman tersebut');
    }


    public function update(Request $request)
    {
        if (User::setIjin('Jurnal Cut Off')) {
            $data   =   Stok::where('id', $request->id)
                        ->where('delivery_id', NULL)
                        ->first() ;

            if ($data) {
                if ($data->qty_awal == $data->stock_opname) {
                    $data->qty_awal     =   $request->jumlah ;
                    $data->stock_opname =   $request->jumlah ;
                    $data->tanggal      =   $request->tanggal ;
                    $data->save() ;
                } else {
                    $result['status']   =   400;
                    $result['msg']      =   "Item sudah digunakan";
                    return $result;
                }
            } else {
                $result['status']   =   400;
                $result['msg']      =   "Data tidak ditmeukan";
                return $result;
            }
        }
        return redirect()->route('home')->with('error', 'Anda tidak memiliki hak akses ke halaman tersebut');
    }


    public function destroy(Request $request)
    {
        if (User::setIjin('Jurnal Cut Off')) {
            $data   =   Stok::where('id', $request->id)
                        ->where('delivery_id', NULL)
                        ->first() ;

            if ($data) {
                if ($data->qty_awal == $data->stock_opname) {
                    $data->delete() ;
                } else {
                    $result['status']   =   400;
                    $result['msg']      =   "Item sudah digunakan";
                    return $result;
                }
            } else {
                $result['status']   =   400;
                $result['msg']      =   "Data tidak ditmeukan";
                return $result;
            }
        }
        return redirect()->route('home')->with('error', 'Anda tidak memiliki hak akses ke halaman tersebut');
    }
}

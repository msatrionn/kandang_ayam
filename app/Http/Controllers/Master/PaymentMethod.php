<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Auth\User;
use App\Models\Master\Setup;
use Illuminate\Http\Request;

class PaymentMethod extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        if (User::setIjin('Daftar Metode Pembayaran')) {
            $q      =   $request->q ?? '';
            $data   =   Setup::where('slug', 'payment')
                        ->orderBy('nama', 'ASC')
                        ->get();

            $data   =   $data->filter(function ($item) use ($q) {
                            $res = true;
                            if ($q != "") {
                                $res =  (false !== stripos($item->nama, $q)) ||
                                        (false !== stripos($item->tipe_kas, $q));
                            }
                            return $res;
                        });

            $data   =   $data->paginate(30);

            return view('master.payment_method.index', compact('data', 'q'));
        }
        return redirect()->route('home')->with('error', 'Anda tidak memiliki hak akses ke halaman tersebut');
    }

    public function store(Request $request)
    {
        if (User::setIjin('Daftar Metode Pembayaran')) {
            $request->validate([
                'nama_pembayaran'   =>  'required|string',
                'jenis_pembayaran'  =>  'required|in:tangan,bank',
            ]);

            $payment            =   new Setup;
            $payment->slug      =   'payment';
            $payment->nama      =   $request->nama_pembayaran;
            $payment->status    =   ($request->jenis_pembayaran == 'tangan') ? 1 : 2;
            $payment->save();

            return back()->with('status', 'Tambah metode pembayaran berhasil');
        }
        return redirect()->route('home')->with('error', 'Anda tidak memiliki hak akses ke halaman tersebut');
    }

    public function update(Request $request)
    {
        if (User::setIjin('Daftar Metode Pembayaran')) {
            $payment            =   Setup::find($request->x_code);
            $payment->nama      =   $request->nama_pembayaran;
            $payment->status    =   ($request->jenis_pembayaran == 'tangan') ? 1 : 2;
            $payment->save();

            if ($payment) {
                return back()->with('status', 'Ubah metode pembayaran berhasil');
            } else {
                return back()->with('error', 'Ubah metode pembayaran gagal');
            }
        }
        return redirect()->route('home')->with('error', 'Anda tidak memiliki hak akses ke halaman tersebut');
    }

    public function destroy(Request $request)
    {
        if (User::setIjin('Daftar Metode Pembayaran')) {
            $return =   Setup::find($request->x_code);
            if (COUNT($return->listpay) < 1) {
                $return->delete();
                return back()->with('status', 'Hapus metode pembayaran berhasil');
            }
            return back()->with('error', 'Ubah metode pembayaran gagal');
        }
        return redirect()->route('home')->with('error', 'Anda tidak memiliki hak akses ke halaman tersebut');
    }
}

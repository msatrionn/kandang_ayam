<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Auth\User;
use App\Models\Master\CRM;
use App\Models\Transaksi\HeaderTrans;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use PDF;

class Konsumen extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        if (User::setIjin('Daftar Konsumen')) {
            $q      =   $request->q ?? '';
            $data   =   CRM::orderBy('nama_konsumen', 'ASC')
                        ->get();

            $data   =   $data->filter(function ($item) use ($q) {
                            $res = true;
                            if ($q != "") {
                                $res =  (false !== stripos($item->nama_konsumen, $q)) ||
                                        (false !== stripos($item->telepon, $q)) ||
                                        (false !== stripos($item->alamat, $q));
                            }
                            return $res;
                        });

            $data   =   $data->paginate(10);

            return view('master.konsumen.index', compact('data', 'q'));
        }
        return redirect()->route('home')->with('error', 'Anda tidak memiliki hak akses ke halaman tersebut');
    }

    public function store(Request $request)
    {
        if (User::setIjin('Daftar Konsumen')) {
            $request->validate([
                'nama_konsumen' =>  'required|string',
                'nomor_telepon' =>  'required|string',
                'alamat'        =>  'required|string',
            ]);

            DB::beginTransaction();

            $user               =   new User;
            $user->name         =   $request->nama_konsumen;
            $user->type         =   'konsumen';
            if (!$user->save()) {
                DB::rollBack();
            }

            $crm                =   new CRM ;
            $crm->id            =   $user->id ;
            $crm->nama_konsumen =   $request->nama_konsumen ;
            $crm->telepon       =   $request->nomor_telepon ;
            $crm->alamat        =   $request->alamat ;
            if (!$crm->save()) {
                DB::rollBack();
            }

            DB::commit();

            return back()->with('status', 'Tambah konsumen berhasil');
        }
        return redirect()->route('home')->with('error', 'Anda tidak memiliki hak akses ke halaman tersebut');
    }

    public function update(Request $request)
    {
        if (User::setIjin('Daftar Konsumen')) {
            DB::beginTransaction();

            $user               =   User::find($request->x_code);
            $user->name         =   $request->nama_konsumen;
            if (!$user->save()) {
                DB::rollBack();
            }

            $crm                =   CRM::find($request->x_code);
            $crm->nama_konsumen =   $request->nama_konsumen;
            $crm->telepon       =   $request->nomor_telepon;
            $crm->alamat        =   $request->alamat;
            if (!$crm->save()) {
                DB::rollBack();
            }

            DB::commit();

            if ($user) {
                return back()->with('status', 'Ubah konsumen berhasil');
            } else {
                return back()->with('error', 'Ubah konsumen gagal');
            }
        }
        return redirect()->route('home')->with('error', 'Anda tidak memiliki hak akses ke halaman tersebut');
    }

    public function detail(Request $request)
    {
        if (User::setIjin('Daftar Konsumen')) {
            $data   =   CRM::find($request->x_code);

            if ($data) {

                $trans  =   HeaderTrans::where('konsumen_id', $data->id)
                            ->get();

                // $pdf    =   App::make('dompdf.wrapper');
                // $pdf->getDomPDF()->set_option("enable_php", true);
                // $pdf->loadHTML(view('master.konsumen.pdf', compact('data', 'trans')))->setPaper('A4', 'landscape');
                // return $pdf->stream();

                $pdf    =   PDF::loadHTML(view('master.konsumen.pdf', compact('data', 'trans')))->setPaper('A4', 'landscape');
                $pdf->getDomPDF()->set_option("enable_php", true);
                return $pdf->download('Laporan Konsumen ' . $data->nama_konsumen . '.pdf');
            }

            return redirect()->route('konsumen.index');
        }
        return redirect()->route('home')->with('error', 'Anda tidak memiliki hak akses ke halaman tersebut');
    }

    public function destroy(Request $request)
    {
        if (User::setIjin('Daftar Konsumen')) {
            $return =   CRM::find($request->x_code);
            if (COUNT($return->listtrans) < 1) {
                $return->delete();
                return back()->with('status', 'Hapus konsumen berhasil');
            }
            return back()->with('error', 'Ubah konsumen gagal');
        }
        return redirect()->route('home')->with('error', 'Anda tidak memiliki hak akses ke halaman tersebut');
    }
}

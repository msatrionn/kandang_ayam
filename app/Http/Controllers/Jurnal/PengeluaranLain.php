<?php

namespace App\Http\Controllers\Jurnal;

use App\Http\Controllers\Controller;
use App\Models\Auth\User;
use App\Models\Jurnal\Angkatan;
use App\Models\Master\Setup;
use App\Models\Transaksi\HeaderTrans;
use App\Models\Transaksi\ListTrans;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Tanggal;

class PengeluaranLain extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        if (User::setIjin('Pengeluaran Lain')) {
            if ($request->key == 'unduh') {
                if ($request->mulai || $request->selesai) {
                    header("Content-type: application/csv");
                    header("Content-Disposition: attachment; filename=Pengeluaran Lain Periode " . Tanggal::date($request->mulai) . " - " . Tanggal::date($request->selesai) . ".csv");
                    $fp = fopen('php://output', 'w');
                    fputcsv($fp, ["sep=,"]);

                    $data   =   [
                        "No",
                        "Tanggal",
                        "Kas",
                        "Nominal",
                        "Keterangan",
                        "Kandang",
                    ];
                    fputcsv($fp, $data);

                    $pengeluaran    =   HeaderTrans::where('jenis', 'pengeluaran_lain')
                        ->whereBetween('tanggal', [$request->mulai, $request->selesai])
                        ->orderByRaw('id DESC, tanggal DESC')
                        ->where('status', 1)
                        ->get();

                    foreach ($pengeluaran as $i => $row) {
                        $data   =   [
                            ++$i,
                            $row->tanggal,
                            $row->method->nama,
                            $row->total_trans,
                            $row->keterangan,
                            $row->kandang_id ? $row->kandang->nama : ''
                        ];
                        fputcsv($fp, $data);
                    }

                    fclose($fp);
                    return '';
                }
                return back()->with('error', 'Pilih tanggal untuk unduh data');
            } else {
                $payment    =   Setup::where('slug', 'payment')
                    ->pluck('nama', 'id');

                $kandang    =   Setup::where('slug', 'kandang')
                    ->orderBy('nama', 'ASC')
                    ->pluck('nama', 'id');

                $data       =   HeaderTrans::select("tanggal")->where('jenis', 'pengeluaran_lain')
                    ->orderByRaw('tanggal DESC')
                    ->where('status', 1)->groupBy("tanggal")
                    ->paginate(10);

                $angkatan       =   Angkatan::orderBy('id', 'asc')->pluck('id', 'no');
                return view('jurnal.pengeluaran_lain.index', compact('payment', 'data', 'angkatan', 'kandang'));
            }
        }
        return redirect()->route('home')->with('error', 'Anda tidak memiliki hak akses ke halaman tersebut');
    }
    public function riwayat(Request $request)
    {
        if (User::setIjin('Pengeluaran Lain')) {
            $payment    =   Setup::where('slug', 'payment')
                ->pluck('nama', 'id');

            $kandang    =   Setup::where('slug', 'kandang')
                ->orderBy('nama', 'ASC')
                ->pluck('nama', 'id');
            $angkatan       =   Angkatan::orderBy('no', 'asc')->get();

            $carian = $request->cari;

            $send = HeaderTrans::where('jenis', 'pengeluaran_lain')
                ->orderByRaw('id DESC, tanggal DESC')
                //Query where dengan function
                ->where(function ($query) use ($request) {
                    $query->orwhere('keterangan', 'like', '%' . $request->cari_produk . '%');
                    $query->orwhere('payment', 'like', '%' . $request->cari_produk . '%');
                })
                ->where('status', 1)
                ->get();
            $arr = [];
            foreach ($send as $key => $value) {
                array_push($arr, $value->tanggal);
            }
            $data       =   HeaderTrans::select("tanggal")->where('jenis', 'pengeluaran_lain')
                ->orderByRaw('tanggal DESC')
                ->where('tanggal', 'like', '%' . $request->cari . '%')
                ->whereIn('tanggal', $arr)
                ->where('status', 1)->groupBy("tanggal")
                ->paginate(10);


            return view('jurnal.pengeluaran_lain.riwayat', compact('send', 'carian', 'payment', 'data', 'angkatan', 'kandang', 'arr'));
        }
        return redirect()->route('home')->with('error', 'Anda tidak memiliki hak akses ke halaman tersebut');
    }

    public function store(Request $request)
    {
        if (User::setIjin('Pengeluaran Lain')) {
            $cek_valid  =   [
                'tanggal'               =>  'required|date',
                'nominal_pengeluaran'   =>  'required',
                'keterangan'            =>  'required',
            ];

            if ($request->check_kas) {
                $setoran    =   [
                    "tulis_kas"         =>  'required|string'
                ];
            } else {
                $setoran    =   [
                    'select_kas'        =>  ['required', Rule::exists('setup', 'id')->where('slug', 'payment')],
                ];
            }

            $validasi   =   Validator::make($request->all(), array_merge($cek_valid, $setoran));

            if ($validasi->fails()) {
                return back()->withErrors($validasi)->withInput();
            }


            for ($i = 0; $i < count($request->nominal_pengeluaran); $i++) {
                DB::beginTransaction();

                $modal                  =   new HeaderTrans;
                // $modal->kandang_id      =   (!$request->angkatan) ? NULL : (($request->angkatan == 'ALL') ? NULL : $request->angkatan);
                $modal->kandang_id      =   $request->kandang[$i];
                $modal->angkatan_id     =   $request->angkatan;
                $modal->jenis           =   'pengeluaran_lain';
                $modal->nomor           =   HeaderTrans::ambil_nomor($modal->jenis);
                $modal->user_id         =   Auth::user()->id;
                $modal->tanggal         =   $request->tanggal;
                $modal->total_trans     =   $request->nominal_pengeluaran[$i] ?? 0;
                $modal->payment         =   $request->nominal_pengeluaran[$i] ?? 0;

                if ($request->check_kas) {
                    $payment            =   new Setup;
                    $payment->slug      =   'payment';
                    $payment->nama      =   $request->tulis_kas;
                    $payment->status    =   2;
                    if (!$payment->save()) {
                        DB::rollBack();
                    }

                    $modal->payment_method  =   $payment->id;
                } else {
                    $modal->payment_method  =   $request->select_kas;
                }

                $modal->keterangan      =   $request->keterangan[$i] ?? 0;
                $modal->status          =   1;
                if (!$modal->save()) {
                    DB::rollBack();
                }
                $list                  =   new ListTrans;
                $list->header_id       =   $modal->id;
                $list->type            =   $modal->jenis;
                $list->harga_satuan    =   $request->nominal_pengeluaran[$i];
                $list->total_harga     =   $request->nominal_pengeluaran[$i];
                if (!$list->save()) {
                    DB::rollBack();
                }


                DB::commit();
            }



            return back()->with('status', 'Input pengeluaran lain berhasil dilakukan');
        }
        return redirect()->route('home')->with('error', 'Anda tidak memiliki hak akses ke halaman tersebut');
    }

    public function update(Request $request)
    {
        if (User::setIjin('Pengeluaran Lain')) {
            DB::beginTransaction();

            $modal                  =   HeaderTrans::find($request->x_code);
            $modal->tanggal         =   $request->tanggal;
            $modal->kandang_id         =   $request->angkatan;
            $modal->total_trans     =   $request->nominal_pengeluaran;
            $modal->payment         =   $request->nominal_pengeluaran;
            $modal->payment_method  =   $request->select_kas;
            $modal->keterangan      =   $request->keterangan;
            if (!$modal->save()) {
                DB::rollBack();
            }

            $list                  =   ListTrans::where('header_id', $modal->id)->first();
            $list->harga_satuan    =   $request->nominal_pengeluaran;
            $list->total_harga     =   $request->nominal_pengeluaran;
            if (!$list->save()) {
                DB::rollBack();
            }

            DB::commit();

            return back()->with('status', 'Ubah pengeluaran lain berhasil dilakukan');
        }
        return redirect()->route('home')->with('error', 'Anda tidak memiliki hak akses ke halaman tersebut');
    }

    public function destroy(Request $request)
    {
        if (User::setIjin('Pengeluaran Lain')) {
            DB::beginTransaction();

            $modal                  =   HeaderTrans::find($request->x_code);
            $modal->status          =   0;
            if (!$modal->save()) {
                DB::rollBack();
            }

            DB::commit();

            return back()->with('status', 'Hapus pengeluaran lain berhasil dilakukan');
        }
        return redirect()->route('home')->with('error', 'Anda tidak memiliki hak akses ke halaman tersebut');
    }
}

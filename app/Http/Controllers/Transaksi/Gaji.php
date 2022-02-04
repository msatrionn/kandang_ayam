<?php

namespace App\Http\Controllers\Transaksi;

use App\Http\Controllers\Controller;
use App\Models\Auth\User;
use App\Models\Master\Karyawan;
use App\Models\Master\Setup;
use App\Models\Transaksi\Gaji as TransaksiGaji;
use App\Models\Transaksi\LogTrans;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Tanggal;
use PDF;

class Gaji extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        if (User::setIjin('Jurnal Penggajian')) {
            if ($request->key == 'unduh') {
                if ($request->mulai || $request->selesai) {
                    $gaji   =   TransaksiGaji::orderByRaw('id DESC, tanggal DESC')
                        ->whereBetween('tanggal', [$request->mulai, $request->selesai])
                        ->get();

                    header("Content-type: application/csv");
                    header("Content-Disposition: attachment; filename=Data Penggajian Periode " . Tanggal::date($request->mulai) . " - " . Tanggal::date($request->selesai) . ".csv");
                    $fp = fopen('php://output', 'w');
                    fputcsv($fp, ["sep=,"]);

                    $data   =   [
                        "No",
                        "Tanggal",
                        "Nama Karyawan",
                        "Metode Penggajian",
                        "Hari Gaji",
                        "Nominal Gaji",
                        "Overtime",
                        "Perkalian Overtime",
                        "Nominal Overtime",
                        "Potongan Gaji",
                        "Pembayaran Cashbon",
                        "THR",
                        "Bonus",
                        "Keterangan Bonus",
                        "Total Didapat",
                        "Kas",
                        "Operator",
                    ];
                    fputcsv($fp, $data);

                    foreach ($gaji as $i => $row) {
                        $data   =   [
                            ++$i,
                            $row->tanggal,
                            $row->karyawan->name,
                            $row->metode_gaji,
                            "=\"$row->hari_gaji\"",
                            $row->besar_gaji * $row->hari_gaji,
                            $row->overtime,
                            $row->perkalian_overtime,
                            $row->besar_overtime * $row->perkalian_overtime,
                            $row->potong_gaji,
                            $row->cashbon,
                            $row->thr,
                            $row->bonus,
                            $row->keterangan,
                            $row->total_didapat,
                            $row->pay->nama,
                            $row->user->name,
                        ];
                        fputcsv($fp, $data);
                    }

                    return '';
                }
                return back()->with('error', 'Pilih tanggal untuk unduh data');
            } else

            if ($request->key == 'pdf') {
                $data       =   TransaksiGaji::find($request->id);

                if ($data) {
                    $pdf    =   App::make('dompdf.wrapper');
                    $pdf->getDomPDF()->set_option("enable_php", true);
                    $pdf->loadHTML(view('jurnal.gaji.pdf', compact('data')));
                    return $pdf->stream();

                    $pdf    =   PDF::loadHTML(view('jurnal.gaji.pdf', compact('data')));
                    $pdf->getDomPDF()->set_option("enable_php", true);
                    return $pdf->download('Summary Report Purchasing Order.pdf');
                } else {
                    return redirect()->route('gaji.index');
                }
            } else

            if ($request->key == 'input') {
                $karyawan   =   Karyawan::where('gaji_harian', '!=', NULL)
                    ->get();

                $payment    =   Setup::where('slug', 'payment')
                    ->pluck('nama', 'id');

                return view('jurnal.gaji.input', compact('karyawan', 'payment'));
            } else

            if ($request->key == 'riwayat_gaji') {
                $q      =   $request->search ?? '';
                $gaji   =   TransaksiGaji::orderByRaw('id DESC, tanggal DESC')
                    ->get();

                $gaji   =   $gaji->filter(function ($item) use ($q) {
                    $res = true;
                    if ($q != "") {
                        $res =  (false !== stripos($item->karyawan->name, $q)) ||
                            (false !== stripos($item->tanggal, $q)) ||
                            (false !== stripos($item->total_didapat, $q)) ||
                            (false !== stripos(number_format($item->total_didapat), $q)) ||
                            (false !== stripos(Tanggal::date($item->tanggal), $q)) ||
                            (false !== stripos($item->metode_gaji, $q));
                    }
                    return $res;
                });

                $gaji   =   $gaji->paginate(10);

                return view('jurnal.gaji.riwayat', compact('gaji', 'q'));
            } else {

                return view('jurnal.gaji.index');
            }
        }
        return redirect()->route('home')->with('error', 'Anda tidak memiliki hak akses ke halaman tersebut');
    }

    public function store(Request $request)
    {
        if (User::setIjin('Jurnal Penggajian')) {
            if (!$request->tanggal) {
                $result['status']   =   400;
                $result['msg']      =   "Tanggal penggajian wajib diisikan";
                return $result;
            }

            if ($request->check_karyawan == 'on') {
                if ((!$request->tulis_karyawan) || (!$request->tanggal_masuk) || (!$request->gaji_per_hari)) {
                    $result['status']   =   400;
                    $result['msg']      =   "Data karyawan wajib diisikan lengkap";
                    return $result;
                }
            } else {
                $karyawan   =   User::where('type', 'karyawan')
                    ->where('id', $request->nama_karyawan)
                    ->first();

                if (!$karyawan) {
                    $result['status']   =   400;
                    $result['msg']      =   "Karyawan tidak ditemukan";
                    return $result;
                }
            }

            if ($request->metode_gaji == 'bulanan') {
                $gaji   =   str_replace(',', '', $request->gajibulan);
                if ($gaji < 1) {
                    $result['status']   =   400;
                    $result['msg']      =   "Tuliskan besaran gaji";
                    return $result;
                }
                $gajihari   =   ($gaji / 30);
                $harikerja  =   30;
            } else
            if ($request->metode_gaji == 'harian') {
                $gajihari   =   $request->gajihari > 0 ? str_replace(',', '', $request->gajihari) : 0;
                $harikerja  =   $request->harikerja > 0 ? str_replace(',', '', $request->harikerja) : 0;
                $gaji       =   ($gajihari * $harikerja);
                if ($gaji < 1) {
                    $result['status']   =   400;
                    $result['msg']      =   "Tuliskan besaran gaji";
                    return $result;
                }
            } else {
                $result['status']   =   400;
                $result['msg']      =   "Metode gaji tidak ditemukan";
                return $result;
            }

            if ($request->lembur == 'on') {
                if ($request->metode_lembur == "jam") {
                    $overkali       =   $request->jamover > 0 ? str_replace(',', '', $request->jamover) : 0;
                    $overdana       =   $request->overdanajam > 0 ? str_replace(',', '', $request->overdanajam) : 0;
                    $overtime       =   ($overdana * $overkali);
                    if ($overtime < 1) {
                        $result['status']   =   400;
                        $result['msg']      =   "Tuliskan besaran over time";
                        return $result;
                    }
                } else
                if ($request->metode_lembur == "harian") {
                    $overkali       =   $request->hariover > 0 ? str_replace(',', '', $request->hariover) : 0;
                    $overdana       =   $request->overhari > 0 ? str_replace(',', '', $request->overhari) : 0;
                    $overtime       =   ($overdana * $overkali);
                    if ($overtime < 1) {
                        $result['status']   =   400;
                        $result['msg']      =   "Tuliskan besaran over time";
                        return $result;
                    }
                } else {
                    $result['status']   =   400;
                    $result['msg']      =   "Over time tidak ditemukan";
                    return $result;
                }
            }

            if ($request->potongan == 'on') {
                $potong_gaji    =   str_replace(',', '', $request->potong_gaji);
                if ($potong_gaji < 1) {
                    $result['status']   =   400;
                    $result['msg']      =   "Potongan gaji wajib diisikan";
                    return $result;
                }
            }

            if ($request->thr == 'on') {
                $thr_gaji    =   str_replace(',', '', $request->thr_gaji);
                if ($thr_gaji < 1) {
                    $result['status']   =   400;
                    $result['msg']      =   "THR wajib diisikan";
                    return $result;
                }
            }
            if ($request->bonus == 'on') {
                $bonus_gaji    =   str_replace(',', '', $request->bonus_gaji);
                if ($bonus_gaji < 1) {
                    $result['status']   =   400;
                    $result['msg']      =   "Bonus wajib diisikan";
                    return $result;
                }
            }

            if ($request->cashbon == 'on') {
                $nominal_cashbon        =   str_replace(',', '', $request->nominal_cashbon);
                if ($nominal_cashbon < 1) {
                    $result['status']   =   400;
                    $result['msg']      =   "Cashbon wajib diisikan";
                    return $result;
                }
            }

            if ($request->check_kas == 'on') {
                if (!$request->tulis_pembayaran) {
                    $result['status']   =   400;
                    $result['msg']      =   "Metode pembayaran wajib diisikan";
                    return $result;
                }
            } else {
                $payment    =   Setup::where('slug', 'payment')
                    ->where('id', $request->metode_pembayaran)
                    ->first();

                if (!$payment) {
                    $result['status']   =   400;
                    $result['msg']      =   "Metode pembayaran tidak ditemukan";
                    return $result;
                }
            }


            DB::beginTransaction();

            $gaji                       =   new TransaksiGaji;
            $gaji->tanggal              =   $request->tanggal;

            if ($request->check_karyawan) {
                $adduser                =   new User;
                $adduser->name          =   $request->tulis_karyawan;
                $adduser->type          =   'karyawan';
                if (!$adduser->save()) {
                    DB::rollBack();
                    $result['status']   =   400;
                    $result['msg']      =   "Proses gagal";
                    return $result;
                }

                $gaji->karyawan_id      =   $adduser->id;

                $karyawan               =   new Karyawan;
                $karyawan->id           =   $adduser->id;
                $karyawan->nama         =   $adduser->name;
                $karyawan->alamat       =   $request->alamat ?? NULL;
                $karyawan->telepon      =   $request->nomor_telepon ?? NULL;
                $karyawan->tanggal_masuk =   $request->tanggal_masuk;
                $karyawan->gaji_harian  =   $request->gaji_per_hari;
                if (!$karyawan->save()) {
                    DB::rollBack();
                    $result['status']   =   400;
                    $result['msg']      =   "Proses gagal";
                    return $result;
                }
            } else {
                $gaji->karyawan_id      =   $karyawan->id;
            }

            $gaji->metode_gaji          =   $request->metode_gaji;
            $gaji->besar_gaji           =   $gajihari;
            $gaji->hari_gaji            =   $harikerja;
            $gaji->overtime             =   $request->metode_lembur ?? NULL;
            $gaji->perkalian_overtime   =   $overkali ?? 0;
            $gaji->besar_overtime       =   $overdana ?? 0;
            $gaji->potong_gaji          =   $potong_gaji ?? 0;
            $gaji->thr                  =   $thr_gaji ?? 0;
            $gaji->kandang_id           =   $request->kandang ?? 0;
            $gaji->bonus                =   $bonus_gaji ?? 0;
            $gaji->cashbon              =   $nominal_cashbon ?? 0;
            $gaji->keterangan           =   $request->keterangan ?? NULL;
            $gaji->total_didapat        =   (($gaji->besar_gaji * $gaji->hari_gaji) + ($gaji->besar_overtime * $gaji->perkalian_overtime)) - ($gaji->potong_gaji + $gaji->cashbon) + $gaji->thr + $gaji->bonus;

            if ($request->check_kas == 'on') {
                $payment                =   new Setup;
                $payment->slug          =   'payment';
                $payment->nama          =   $request->tulis_pembayaran;
                $payment->status        =   2;
                if (!$payment->save()) {
                    $report['status']   =   400;
                    $report['message']  =   'Proses gagal';
                    return $report;
                }

                $gaji->metode_kas       =   $payment->id;
            } else {
                $gaji->metode_kas       =   $payment->id;
            }

            $gaji->user_id              =   Auth::user()->id;
            if (!$gaji->save()) {
                DB::rollBack();
                $result['status']   =   400;
                $result['msg']      =   "Proses gagal";
                return $result;
            }

            $penggajian              =   new LogTrans;
            $penggajian->table       =   'gaji';
            $penggajian->table_id    =   $gaji->id;
            $penggajian->tanggal     =   $gaji->tanggal;
            $penggajian->kas         =   $gaji->metode_kas;
            $penggajian->jenis       =   'gaji';
            $penggajian->nominal     =   $gaji->total_didapat;
            $penggajian->status      =   1;
            if (!$penggajian->save()) {
                DB::rollBack();
            }

            DB::commit();
        }
        return redirect()->route('home')->with('error', 'Anda tidak memiliki hak akses ke halaman tersebut');
    }


    public function destroy(Request $request)
    {
        if (User::setIjin('Jurnal Penggajian')) {
            $data   =   TransaksiGaji::find($request->x_code);

            if ($data) {
                LogTrans::where('table', 'gaji')
                    ->where('table_id', $data->id)
                    ->where('jenis', 'gaji')
                    ->delete();

                $data->delete();

                return back()->with('status', 'Hapus gaji berhasil');
            }

            return back()->with('error', 'Hapus gaji gagal');
        }
        return redirect()->route('home')->with('error', 'Anda tidak memiliki hak akses ke halaman tersebut');
    }
}

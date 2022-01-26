<?php

namespace App\Http\Controllers\Jurnal;

use App\Http\Controllers\Controller;
use App\Models\Auth\User;
use App\Models\Jurnal\Bon as JurnalBon;
use App\Models\Master\Karyawan;
use App\Models\Master\Setup;
use App\Models\Transaksi\LogTrans;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Tanggal;

class Bon extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        if (User::setIjin('Jurnal Cashbon')) {
            if ($request->key == 'unduh') {
                if ($request->mulai || $request->selesai) {
                    header("Content-type: application/csv");
                    header("Content-Disposition: attachment; filename=Cashbon Periode " . Tanggal::date($request->mulai) . " - " . Tanggal::date($request->selesai) . ".csv");
                    $fp = fopen('php://output', 'w');
                    fputcsv($fp, ["sep=,"]);

                    $data   =   [
                        "No",
                        "Tanggal",
                        "Nama Karyawan",
                        "Nominal",
                        "Metode Pembayaran",
                    ];
                    fputcsv($fp, $data);

                    $jurnal =   JurnalBon::whereBetween('tanggal', [$request->mulai, $request->selesai])
                                ->get();

                    foreach ($jurnal as $i => $row) {
                        $data   =   [
                            ++$i,
                            $row->tanggal,
                            $row->karyawan->nama,
                            $row->nominal,
                            $row->setup->nama ?? '',
                        ];
                        fputcsv($fp, $data);
                    }

                    fclose($fp);
                    return '';
                }
                return back()->with('error', 'Pilih tanggal untuk unduh data');
            } else
            if ($request->key == 'input') {
                $karyawan   =   Karyawan::where('gaji_harian', '!=', NULL)
                                ->get();

                return view('jurnal.cashbon.input', compact('karyawan'));
            } else
            if ($request->key == 'detail') {
                $data   =   Karyawan::where('gaji_harian', '!=', NULL)
                            ->where('id', $request->id)
                            ->first();

                if ($data) {
                    $payment    =   Setup::where('slug', 'payment')
                                    ->pluck('nama', 'id');

                    return view('jurnal.cashbon.input_detail', compact('data', 'payment'));
                } else {
                    return "Karyawan tidak ditemukan";
                }
            } else
            if ($request->key == 'riwayat') {
                $data   =   JurnalBon::where('karyawan_id', $request->id)
                            ->get();

                if ($data) {
                    return view('jurnal.cashbon.riwayat', compact('data'));
                } else {
                    return "Karyawan tidak ditemukan";
                }
            } else {
                return view('jurnal.cashbon.index');
            }
        }
        return redirect()->route('home')->with('error', 'Anda tidak memiliki hak akses ke halaman tersebut');
    }

    public function store(Request $request)
    {
        if (User::setIjin('Jurnal Cashbon')) {
            $karyawan   =   Karyawan::where('gaji_harian', '!=', NULL)
                            ->where('id', $request->karyawan)
                            ->first();

            if (!$karyawan) {
                $result['status']   =   400;
                $result['msg']      =   "Karyawan tidak ditemukan";
                return $result;
            }

            if (!$request->tanggal) {
                $result['status']   =   400;
                $result['msg']      =   "Tanggal wajib diisikan";
                return $result;
            }

            $nominal    =   str_replace(',', '', $request->nominal);

            if ($nominal < 1) {
                $result['status']   =   400;
                $result['msg']      =   "Nominal cashbon wajib diisikan";
                return $result;
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

            $data               =   new JurnalBon;
            $data->karyawan_id  =   $karyawan->id;
            $data->tanggal      =   $request->tanggal;
            $data->nominal      =   $nominal;

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

                $data->payment_id       =   $payment->id;
            } else {
                $data->payment_id       =   $request->metode_pembayaran;
            }

            if (!$data->save()) {
                DB::rollBack();
                $result['status']   =   400;
                $result['msg']      =   "Proses gagal";
                return $result;
            }

            $cash_bon               =   new LogTrans;
            $cash_bon->table        =   'cashbon';
            $cash_bon->table_id     =   $data->id;
            $cash_bon->tanggal      =   $data->tanggal;
            $cash_bon->kas          =   $data->payment_id ;
            $cash_bon->jenis        =   'cashbon';
            $cash_bon->nominal      =   $nominal ;
            $cash_bon->status       =   1;
            if (!$cash_bon->save()) {
                DB::rollBack();
            }

            DB::commit();
        }
        return redirect()->route('home')->with('error', 'Anda tidak memiliki hak akses ke halaman tersebut');
    }
}

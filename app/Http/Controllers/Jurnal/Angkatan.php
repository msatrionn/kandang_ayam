<?php

namespace App\Http\Controllers\Jurnal;

use App\Http\Controllers\Controller;
use App\Imports\RecordingImport;
use App\Imports\TimbangImport;
use Illuminate\Http\Request;
use App\Models\Transaksi\Delivery;
use App\Models\Master\Setup;
use App\Models\Master\Produk;
use App\Models\Jurnal\Populasi;
use App\Models\Jurnal\Angkatan as ModelAngkatan;
use App\Models\Jurnal\RiwayatKandang;
use App\Models\Master\Stok;
use App\Models\Jurnal\KartuStok;
use App\Models\Jurnal\Vaksinasi;
use App\Models\Jurnal\Timbang;
use App\Models\Jurnal\Catatan;
use App\Models\Auth\User;
use App\Models\Jurnal\Kasus;
use App\Models\Jurnal\StokKandang;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;

use File;

class Angkatan extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }


    public function index(Request $request)
    {
        if (User::setIjin('Jurnal Angkatan Ayam')) {
            if ($request->key == 'show_kandang') {
                $listriwayat    =   RiwayatKandang::where('angkatan', $request->row_id)
                    ->orderBy('tanggal', 'DESC')
                    ->get();

                $angkatan       =   $request->row_id;

                return view('jurnal.angkatan.input_kandang', compact('listriwayat', 'angkatan'));
            } else

            if ($request->key == 'show_data') {
                $tab            =   $request->tab ?? '';
                $kandang        =   RiwayatKandang::with('produk')->find($request->kandang);
                // dd($kandang);
                $strain         =   Setup::where('slug', 'strain')->get();

                $riwayat_pakan  =   [];
                $hitung_pakan   =   [];
                $riwayat_ovk    =   [];
                $hitung_ovk     =   [];
                $riwayat_vaksin =   [];

                $riwayat_pakan  =   KartuStok::where('recording_id', $kandang->id)
                    ->where('tipekartu', 'pakan')
                    ->orderBy('hari', 'DESC')
                    ->get();

                $masuk_pakan    =   0;
                $keluar_pakan   =   0;
                foreach ($riwayat_pakan as $item) {
                    $masuk_pakan    +=  $item->masuk;
                    $keluar_pakan   +=  $item->keluar;
                }

                $hitung_pakan   =   [
                    'masuk'     =>  $masuk_pakan,
                    'keluar'    =>  $keluar_pakan
                ];

                $riwayat_ovk    =   KartuStok::where('recording_id', $kandang->id)
                    ->where('tipekartu', 'ovk')
                    ->orderBy('hari', 'DESC')
                    ->get();

                $masuk_ovk      =   0;
                $keluar_ovk     =   0;
                foreach ($riwayat_ovk as $item) {
                    $masuk_ovk    +=  $item->masuk;
                    $keluar_ovk   +=  $item->keluar;
                }

                $hitung_ovk     =   [
                    'masuk'     =>  $masuk_ovk,
                    'keluar'    =>  $keluar_ovk
                ];

                $riwayat_vaksin =   Vaksinasi::where('riwayat_id', $kandang->id)
                    ->orderBy('umur', 'ASC')
                    ->get();

                $penyakit       =   Setup::where('slug', 'penyakit')->get();

                $daftar_penyait =   Kasus::where('riwayat_id', $kandang->id)
                    ->where('kandang', $kandang->kandang)
                    ->orderBy('tanggal', 'DESC')
                    ->get();

                $daftar_kandang =   Setup::where('slug', 'kandang')
                    ->where('status', 1)
                    ->orderBy('nama', 'ASC')
                    ->get();

                if ($request->act == 'unduh_recording') {
                    return view('jurnal.angkatan.record_excel', compact('kandang'));
                } else {
                    return view('jurnal.angkatan.input_jurnal', compact('kandang', 'tab', 'strain', 'kandang', 'riwayat_pakan', 'hitung_pakan', 'riwayat_ovk', 'hitung_ovk', 'riwayat_vaksin', 'penyakit', 'daftar_penyait', 'daftar_kandang'));
                }
            } else
            if ($request->key == 'detail_jurnal') {
                $data       =   Delivery::find($request->angkatan_id);

                $hari       =   $request->hari ?? '';

                $pakan      =   Produk::where('tipe', 2)
                    ->where('jenis', NULL)
                    ->get();

                $vaksin     =   Produk::where('tipe', 1)
                    ->where('jenis', NULL)
                    ->get();

                return view('jurnal.angkatan.detail_jurnal', compact('data', 'hari', 'pakan', 'vaksin'));
            } else
            if ($request->key == 'hari_pakan') {
                $hari       =   $request->hari ?? '';

                $data       =   RiwayatKandang::select('tanggal', 'kandang')
                    ->where('id', $request->kandang)
                    ->where('angkatan', $request->row_id)
                    ->first();

                $pakan      =   StokKandang::select(DB::raw("SUM(sisa) AS stock_sisa"), 'produk_id')
                    ->where('tipe', 2)
                    ->where('kandang_id', $data->kandang)
                    ->where('sisa', '>', 0)
                    ->groupBy('produk_id')
                    ->get();

                $penerima   =   User::where('type', 'karyawan')
                    ->get();

                return view('jurnal.angkatan.tabs.pakan_view', compact('hari', 'data', 'pakan', 'penerima'));
            } else
            if ($request->key == 'umur_vaksinasi') {

                $umur       =   $request->umur ?? '';

                $data       =   RiwayatKandang::select('tanggal', 'kandang')
                    ->where('id', $request->kandang)
                    ->where('angkatan', $request->row_id)
                    ->first();

                $vaksin     =   StokKandang::select('produk_id')
                    ->where('tipe', 1)
                    ->where('kandang_id', $data->kandang)
                    ->groupBy('produk_id')
                    ->get();

                return view('jurnal.angkatan.tabs.vaksinasi_view', compact('umur', 'data', 'vaksin'));
            } else
            if ($request->key == 'hari_ovk') {
                $hari       =   $request->hari ?? '';

                $data       =   RiwayatKandang::where('id', $request->kandang)
                    ->where('angkatan', $request->row_id)
                    ->first();

                $ovk        =   StokKandang::select(DB::raw("SUM(sisa) AS stock_sisa"), 'produk_id')
                    ->whereIn('tipe', [1, 3])
                    ->where('kandang_id', $data->kandang)
                    ->where('sisa', '>', 0)
                    ->groupBy('produk_id')
                    ->get();

                $penerima   =   User::where('type', 'karyawan')
                    ->get();

                return view('jurnal.angkatan.tabs.ovk_view', compact('hari', 'data', 'ovk', 'penerima'));
            } else
            if ($request->key == 'hari_populasi') {
                $hari       =   $request->hari ?? '';

                $data       =   RiwayatKandang::where('id', $request->kandang)
                    ->where('angkatan', $request->row_id)
                    ->first();

                $record     =   Populasi::where('riwayat_id', $request->kandang)
                    ->where('hari', $hari)
                    ->first();

                return view('jurnal.angkatan.tabs.populasi_view', compact('hari', 'data', 'record'));
            } else
            if ($request->key == 'hari_timbang') {
                $hari       =   $request->hari ?? '';

                $data       =   RiwayatKandang::where('id', $request->kandang)
                    ->where('angkatan', $request->row_id)
                    ->first();

                $timbang    =   Timbang::where('riwayat_id', $data->id)
                    ->where('hari', $hari)
                    ->first();

                return view('jurnal.angkatan.tabs.timbang_view', compact('hari', 'data', 'timbang'));
            } else
            if ($request->key == 'hari_catatan') {
                $hari       =   $request->hari ?? '';

                $data       =   RiwayatKandang::where('id', $request->kandang)
                    ->where('angkatan', $request->row_id)
                    ->first();

                $catatan    =   Catatan::where('riwayat_id', $data->id)
                    ->where('hari', $hari)
                    ->first();

                return view('jurnal.angkatan.tabs.keterangan_view', compact('hari', 'data', 'catatan'));
            } else {
                $data   =   ModelAngkatan::where('status', 1)
                    ->orderBy('no', 'ASC')
                    ->get();
                return view('jurnal.angkatan.index', compact('data'));
            }
        }
        return redirect()->route('home')->with('error', 'Anda tidak memiliki hak akses ke halaman tersebut');
    }

    public function store(Request $request)
    {
        if (User::setIjin('Jurnal Angkatan Ayam')) {
            if ($request->key == 'mutasi') {
                $kandang    =   RiwayatKandang::find($request->pilih_kandang);

                if (!$request->tanggal_mutasi) {
                    $report['status']   =   400;
                    $report['message']  =   'Pilih tanggal mutasi';
                    return $report;
                } else {
                    if ($request->jumlah_mutasi > $kandang->populasi_akhir) {
                        $report['status']   =   400;
                        $report['message']  =   'Jumlah mutasi melebihi ayam tersedia';
                        return $report;
                    } else {
                        $farm   =   Setup::find($request->penempatan_kandang);
                        if ($farm->status == 2) {
                            $report['status']   =   400;
                            $report['message']  =   'Kandang sudah digunakan';
                            return $report;
                        } else {

                            DB::beginTransaction();

                            $hari                       =   date_diff(date_create($request->tanggal_mutasi), date_create($kandang->tanggal));
                            $record                     =   Populasi::where('hari', $hari->d)
                                ->where('riwayat_id', $kandang->id)
                                ->first() ?? new Populasi;

                            $record->riwayat_id         =   $kandang->id;
                            $record->kandang            =   $kandang->kandang;
                            $record->tanggal_masuk      =   $kandang->tanggal;
                            $record->hari               =   $hari->d + 1;
                            $record->tanggal_input      =   $request->tanggal_mutasi;
                            $record->populasi_mati      =   $record->populasi_mati ?? 0;
                            $record->populasi_afkir     =   $record->populasi_afkir ?? 0;
                            $record->populasi_panen     =   $record->populasi_panen + $request->jumlah_mutasi;
                            if (!$record->save()) {
                                DB::rollBack();
                                $report['status']   =   400;
                                $report['message']  =   'Proses mutasi gagal';
                                return $report;
                            }

                            $riwayat                        =   new RiwayatKandang;
                            $riwayat->angkatan              =   $request->angkatan;
                            $riwayat->strain_id             =   $kandang->strain_id;
                            $riwayat->tanggal               =   $kandang->tanggal;
                            $riwayat->kandang               =   $farm->id;
                            $riwayat->populasi              =   $request->jumlah_mutasi;
                            $riwayat->status                =   1;
                            if (!$riwayat->save()) {
                                DB::rollBack();
                                $data['status'] =   400;
                                $data['msg']    =   "Proses Gagal";
                                return $data;
                            }

                            $farm->status       =   2;
                            if (!$farm->save()) {
                                DB::rollBack();
                                $data['status'] =   400;
                                $data['msg']    =   "Proses Gagal";
                                return $data;
                            }

                            DB::commit();

                            if ($kandang->populasi_akhir == 0) {
                                $on_kandang         =   Setup::find($kandang->kandang);
                                $on_kandang->status =   1;
                                $on_kandang->save();

                                $kandang->status    =   2;
                                $kandang->save();
                            }
                        }
                    }
                }
            } else
            if ($request->key == 'info') {
                dd($request->all());
                $cek_valid  =   [
                    'angkatan'      =>  ['required', Rule::exists('angkatan', 'id')->where('status', 1)],
                    'pilih_kandang' =>  ['required', Rule::exists('riwayatkandang', 'id')->where('status', 1)],
                ];

                // if ($request->input_strain == 'true') {
                //     $strain_input   =   [
                //         'strain'    =>  'required',
                //     ];
                // } else {
                //     $strain_input   =   [
                //         'strain_select' =>  ['required', Rule::exists('setup', 'id')->where('slug', 'strain')],
                //     ];
                // }

                $validasi   =   Validator::make($request->all(), $cek_valid);

                if ($validasi->fails()) {
                    $data['status']     =   0;
                    $data['message']    =   'Data tidak lengkap';
                    return $data;
                }


                DB::beginTransaction();

                // Mengambil data angkatan
                $kandang                    =   RiwayatKandang::find($request->pilih_kandang);
                $kandang->strain_id     =   $request->strain_select;
                // Jika strain input sendiri
                // if ($request->input_strain == 'true') {
                //     $strain                 =   new Setup;
                //     $strain->slug           =   'strain';
                //     $strain->nama           =   $request->strain;
                //     $strain->status         =   1;
                //     if (!$strain->save()) {
                //         DB::rollBack();
                //     }

                // $kandang->strain_id     =   $strain->id;
                // } else {

                // }

                if (!$kandang->save()) {
                    DB::rollBack();
                }

                DB::commit();
                $data['status']     =   1;
                $data['message']    =   'Data berhasil diperbaharui';
                return $data;
            }


            if ($request->key == 'ubahriwayat') {
                $data       =   RiwayatKandang::find($request->riwayat);
                $angkatan   =   ModelAngkatan::find($request->row_id);

                if ($data->angkatan == $angkatan->id) {
                    $angkatan->kandang_sekarang =   $request->penempatan_kandang;
                    $angkatan->save();
                }

                $data->kandang  =   $request->penempatan_kandang;
                $data->tanggal  =   $request->tanggal_pindah;
                $data->save();
            }


            if ($request->key == 'pakan') {
                $data       =   RiwayatKandang::find($request->kandang);


                $validasi   =   Validator::make($request->all(), [
                    'hari_pakan'        =>  'required',
                    'jenis_pakan'       =>  'required',
                ]);

                if ($validasi->fails()) {
                    $report['status']   =   400;
                    $report['message']  =   'Data tidak lengkap';
                    return $report;
                }

                if ($request->pakan_keluar > 0) {
                    if ($request->pakan_keluar > StokKandang::where('produk_id', $request->jenis_pakan)->where('kandang_id', $data->kandang)->sum('sisa')) {
                        $report['status']   =   400;
                        $report['message']  =   'Sisa stok pakan dipilih tidak mencukupi';
                        return $report;
                    }
                }

                DB::beginTransaction();

                if (empty(KartuStok::where('hari', $request->hari_pakan)->where('tipekartu', 'pakan')->first()->masuk)) {
                    $kartu                  =   new KartuStok;
                    $kartu->tipekartu       =   'pakan';
                    $kartu->recording_id    =   $data->id;
                    $kartu->tanggal_masuk   =   $data->tanggal;
                    $kartu->tanggal_kartu   =   Carbon::parse($data->tanggal)->addDays(($request->hari_pakan - 1));
                    $kartu->hari            =   $request->hari_pakan;
                    $kartu->jenis           =   $request->jenis_pakan;
                    $kartu->masuk           =   $request->pakan_masuk ?? 0;
                    $kartu->keluar          =   $request->pakan_keluar ?? 0;

                    if ($request->check_penerima == 'true') {
                        $user               =   new User;
                        $user->name         =   $request->input_penerima;
                        $user->type         =   'karyawan';
                        if (!$user->save()) {
                            DB::rollBack();
                            $report['status']   =   400;
                            $report['message']  =   'Proses gagal';
                            return $report;
                        }

                        $penerima           =   $user->id;
                    } else {
                        $penerima           =   $request->pilih_penerima;
                    }

                    $kartu->penerima        =   $penerima;
                    $kartu->keterangan      =   $request->keterangan_pakan;
                    if (!$kartu->save()) {
                        DB::rollBack();
                        $report['status']   =   400;
                        $report['message']  =   'Proses gagal';
                        return $report;
                    }
                    // dd([$request->all(), $request->pakan_masuk]);

                    if ($request->pakan_keluar > 0) {
                        $pakan  =   StokKandang::where('produk_id', $request->jenis_pakan)
                            ->where('kandang_id', $data->kandang)
                            ->get();

                        $sisa   =   $request->pakan_keluar;
                        foreach ($pakan as $row) {
                            if ($sisa > 0) {
                                if ($sisa >= $row->sisa) {
                                    $ambil  =   $row->sisa;
                                } else {
                                    $ambil  =   $sisa;
                                }

                                $sisa       =   ($sisa - $ambil);
                                $row->sisa  =   ($row->sisa - $ambil);
                                if (!$row->save()) {
                                    DB::rollBack();
                                    $report['status']   =   400;
                                    $report['message']  =   'Proses gagal';
                                    return $report;
                                }
                            }
                        }
                    }
                    if ($request->pakan_masuk > 0) {
                        $pakan  =   StokKandang::where('produk_id', $request->jenis_pakan)
                            ->where('kandang_id', $data->kandang)
                            ->orderBy('sisa', 'desc')
                            ->take(1)
                            ->first();
                        $pakan->sisa = $pakan->sisa + $request->pakan_masuk;
                        return $pakan->save();
                    }
                    DB::commit();
                    $data['status']     =   200;
                    $data['message']    =   'Sukses';
                    return $data;
                } else {
                    // dd('cek');
                    if ($request->check_penerima == 'true') {
                        $user               =   new User;
                        $user->name         =   $request->input_penerima;
                        $user->type         =   'karyawan';
                        if (!$user->save()) {
                            DB::rollBack();
                            $report['status']   =   400;
                            $report['message']  =   'Proses gagal';
                            return $report;
                        }

                        $penerima           =   $user->id;
                    } else {
                        $penerima           =   $request->pilih_penerima;
                    }

                    $cek_masuk = KartuStok::where('hari', $request->hari_pakan)->where('tipekartu', 'pakan');
                    $cek_keluar = KartuStok::where('hari', $request->hari_pakan)->where('tipekartu', 'pakan');
                    $stokkandang = StokKandang::where('kandang_id', $data->kandang)->where('tipe', '2')
                        ->where('produk_id', $request->jenis_pakan)
                        ->orderBy('sisa', 'desc')
                        ->take(1)
                        ->first()->sisa;
                    if ($stokkandang >= $request->ovk_keluar) {
                        $sisa_total = $stokkandang - $request->ovk_keluar;
                        $sisa_tot = (int)($sisa_total);
                    }
                    $stokandangs = StokKandang::where('kandang_id', $data->kandang)
                        ->where('tipe', '2')
                        ->where('produk_id', $request->jenis_pakan)
                        ->orderBy('sisa', 'desc')
                        ->take(1)->first();
                    $stokandangs->update(['sisa' => $sisa_tot]);


                    $pakan_masuk = (int)KartuStok::where('hari', $request->hari_pakan)->where('tipekartu', 'pakan')->first()->masuk;
                    $req_masuk = (int)$request->pakan_masuk;
                    $pakan_keluar = (int)KartuStok::where('hari', $request->hari_pakan)->where('tipekartu', 'pakan')->first()->keluar;
                    $req_keluar = (int)$request->pakan_keluar;
                    $cek_masuk->update([
                        'masuk' => $pakan_masuk + $req_masuk,
                        'jenis' => $request->jenis_pakan,
                        'penerima' => $penerima, 'keterangan' => $request->keterangan_pakan

                    ]);
                    $cek_keluar->update([
                        'keluar' => $pakan_keluar + $req_keluar,
                        'jenis' => $request->jenis_pakan, 'penerima' => $penerima,
                        'keterangan' => $request->keterangan_pakan
                    ]);

                    if ($request->check_penerima == 'true') {
                        $user               =   new User;
                        $user->name         =   $request->input_penerima;
                        $user->type         =   'karyawan';
                        if (!$user->save()) {
                            DB::rollBack();
                            $report['status']   =   400;
                            $report['message']  =   'Proses gagal';
                            return $report;
                        }

                        $penerima           =   $user->id;
                    } else {
                        $penerima           =   $request->pilih_penerima;
                    }
                    if ($request->pakan_keluar > 0) {
                        $pakan  =   StokKandang::where('produk_id', $request->jenis_pakan)
                            ->where('kandang_id', $data->kandang)
                            ->orderBy('sisa', 'desc')
                            ->take(1)
                            ->get();

                        $sisa   =   $request->pakan_keluar;
                        foreach ($pakan as $row) {
                            if ($sisa > 0) {
                                if ($sisa >= $row->sisa) {
                                    $ambil  =   $row->sisa;
                                } else {
                                    $ambil  =   $sisa;
                                }

                                $sisa       =   ($sisa - $ambil);
                                $row->sisa  =   ($row->sisa - $ambil);
                                if (!$row->save()) {
                                    DB::rollBack();
                                    $report['status']   =   400;
                                    $report['message']  =   'Proses gagal';
                                    return $report;
                                }
                            }
                        }
                    }
                    if ($request->pakan_masuk > 0) {
                        $pakan  =   StokKandang::where('produk_id', $request->jenis_pakan)
                            ->where('kandang_id', $data->kandang)
                            ->orderBy('sisa', 'desc')
                            ->take(1)
                            ->first();
                        $pakan->sisa = $pakan->sisa + $request->pakan_masuk;
                        return $pakan->save();
                    }

                    DB::commit();
                    $data['status']     =   200;
                    $data['message']    =   'Sukses';
                    return $data;
                }


                $data['status']     =   200;
                $data['message']    =   'Sukses';
                return $data;
            }


            if ($request->key == 'ovk') {
                $angkatan   =   ModelAngkatan::find($request->angkatan);
                $data       =   RiwayatKandang::find($request->kandang);

                $validasi   =   Validator::make($request->all(), [
                    'hari_ovk'          =>  'required',
                    'jenis_ovk'         =>  'required',
                ]);

                if ($validasi->fails()) {
                    $respon['status']   =   400;
                    $respon['message']  =   'Data tidak lengkap';
                    return $respon;
                }

                if ($request->ovk_keluar > 0) {
                    if ($request->ovk_keluar > StokKandang::where('produk_id', $request->jenis_ovk)->where('kandang_id', $data->kandang)->sum('sisa')) {
                        $report['status']   =   400;
                        $report['message']  =   'Sisa stok dipilih tidak mencukupi';
                        return $report;
                    }
                }

                DB::beginTransaction();

                if (empty(KartuStok::where('tipekartu', 'ovk')->where('hari', $request->hari_ovk)->first())) {
                    $kartu                  =   new KartuStok;
                    $kartu->tipekartu       =   'ovk';
                    $kartu->recording_id    =   $data->id;
                    $kartu->tanggal_masuk   =   $data->tanggal;
                    $kartu->tanggal_kartu   =   Carbon::parse($data->tanggal)->addDays(($request->hari_ovk - 1));
                    $kartu->hari            =   $request->hari_ovk;
                    $kartu->jenis           =   $request->jenis_ovk;
                    $kartu->masuk           =   $request->ovk_masuk ?? 0;
                    $kartu->keluar          =   $request->ovk_keluar ?? 0;


                    if ($request->check_penerima == 'true') {
                        $user               =   new User;
                        $user->name         =   $request->input_penerima;
                        $user->type         =   'karyawan';
                        if (!$user->save()) {
                            DB::rollBack();
                            $report['status']   =   400;
                            $report['message']  =   'Proses gagal';
                            return $report;
                        }

                        $penerima           =   $user->id;
                    } else {
                        $penerima           =   $request->pilih_penerima;
                    }

                    $kartu->penerima        =   $penerima;
                    $kartu->keterangan      =   $request->keterangan_ovk;
                    if (!$kartu->save()) {
                        DB::rollBack();
                        $report['status']   =   400;
                        $report['message']  =   'Proses gagal';
                        return $report;
                    }


                    if ($request->ovk_keluar > 0) {
                        $ovk  =   StokKandang::where('produk_id', $request->jenis_ovk)
                            ->where('kandang_id', $data->kandang)
                            ->orderBy('sisa', 'desc')
                            ->take(1)
                            ->get();

                        $sisa   =   $request->ovk_keluar;
                        foreach ($ovk as $row) {
                            if ($sisa > 0) {
                                if ($sisa >= $row->sisa) {
                                    $ambil  =   $row->sisa;
                                } else {
                                    $ambil  =   $sisa;
                                }

                                $sisa       =   ($sisa - $ambil);
                                $row->sisa  =   ($row->sisa - $ambil);
                                if (!$row->save()) {
                                    DB::rollBack();
                                    $report['status']   =   400;
                                    $report['message']  =   'Proses gagal';
                                    return $report;
                                }
                            }
                        }
                    }
                } else {

                    if ($request->check_penerima == 'true') {
                        $user               =   new User;
                        $user->name         =   $request->input_penerima;
                        $user->type         =   'karyawan';
                        if (!$user->save()) {
                            DB::rollBack();
                            $report['status']   =   400;
                            $report['message']  =   'Proses gagal';
                            return $report;
                        }

                        $penerima           =   $user->id;
                    } else {
                        $penerima           =   $request->pilih_penerima;
                    }

                    $cek_masuk = KartuStok::where('hari', $request->hari_ovk)->where('tipekartu', 'ovk');
                    $cek_keluar = KartuStok::where('hari', $request->hari_ovk)->where('tipekartu', 'ovk');
                    $stokkandang = StokKandang::where('kandang_id', $data->kandang)->where('tipe', '1')->where('produk_id', $request->jenis_ovk)->orderBy('sisa', 'desc')
                        ->take(1)->first()->sisa;
                    if ($request->ovk_masuk > 0) {
                        $sisa_total = $stokkandang + $request->ovk_masuk;
                        $sisa_tot = (int)($sisa_total);
                        StokKandang::where('produk_id', $request->jenis_ovk)
                            ->where('kandang_id', $data->kandang)
                            ->orderBy('sisa', 'desc')
                            ->take(1)->update(['sisa' => $sisa_tot]);
                    }
                    // return response()->json([$stokkandang]);
                    // return response()->json([$data]);
                    // if ($stokkandang >= $request->ovk_keluar) {
                    //     // dd($request->all());
                    //     $sisa_total = $stokkandang - $request->ovk_keluar;
                    //     $sisa_tot = (int)($sisa_total);
                    //     // dd($sisa_tot);
                    // }
                    // if ($request->ovk_masuk > 0) {
                    //     // dd($request->all());
                    //     $sisa_total = $stokkandang + $request->ovk_masuk;
                    //     $sisa_tot = (int)($sisa_total);
                    //     // dd($sisa_tot);
                    // }


                    $ovk_masuk = (int)KartuStok::where('hari', $request->hari_ovk)->where('tipekartu', 'ovk')->first()->masuk;
                    $req_masuk = (int)$request->ovk_masuk;
                    $ovk_keluar = (int)KartuStok::where('hari', $request->hari_ovk)->where('tipekartu', 'ovk')->first()->keluar;
                    $req_keluar = (int)$request->ovk_keluar;
                    $cek_masuk->update([
                        'masuk' => $ovk_masuk + $req_masuk,
                        'jenis' => $request->jenis_ovk,
                        'penerima' => $penerima, 'keterangan' => $request->keterangan_ovk

                    ]);
                    $cek_keluar->update([
                        'keluar' => $ovk_keluar + $req_keluar,
                        'jenis' => $request->jenis_ovk, 'penerima' => $penerima,
                        'keterangan' => $request->keterangan_ovk
                    ]);

                    if ($request->check_penerima == 'true') {
                        $user               =   new User;
                        $user->name         =   $request->input_penerima;
                        $user->type         =   'karyawan';
                        if (!$user->save()) {
                            DB::rollBack();
                            $report['status']   =   400;
                            $report['message']  =   'Proses gagal';
                            return $report;
                        }

                        $penerima           =   $user->id;
                    } else {
                        $penerima           =   $request->pilih_penerima;
                    }


                    if ($request->ovk_keluar > 0) {
                        $ovk  =   StokKandang::where('produk_id', $request->jenis_ovk)
                            ->where('kandang_id', $data->kandang)
                            ->orderBy('sisa', 'desc')
                            ->take(1)
                            ->get();

                        $sisa   =   $request->ovk_keluar;
                        foreach ($ovk as $row) {
                            if ($sisa > 0) {
                                if ($sisa >= $row->sisa) {
                                    $ambil  =   $row->sisa;
                                } else {
                                    $ambil  =   $sisa;
                                }

                                $sisa       =   ($sisa - $ambil);
                                $row->sisa  =   ($row->sisa - $ambil);
                                if (!$row->save()) {
                                    DB::rollBack();
                                    $report['status']   =   400;
                                    $report['message']  =   'Proses gagal';
                                    return $report;
                                }
                            }
                        }
                    }

                    DB::commit();
                    $data['status']     =   200;
                    $data['message']    =   'Sukses';
                    return $data;
                }
                DB::commit();

                $data['status']     =   200;
                $data['message']    =   'Sukses';
                return $data;
            }


            if ($request->key == 'populasi') {
                $riwayat                    =   RiwayatKandang::where('id', $request->kandang)
                    ->where('angkatan', $request->angkatan)
                    ->first();

                $record                     =   Populasi::where('hari', $request->hari_populasi)
                    ->where('riwayat_id', $riwayat->id)
                    ->first() ?? new Populasi;

                $record->riwayat_id         =   $riwayat->id;
                $record->kandang            =   $riwayat->kandang;
                $record->tanggal_masuk      =   $riwayat->tanggal;
                $record->hari               =   $request->hari_populasi;
                $record->tanggal_input      =   Carbon::parse($record->tanggal_masuk)->addDays(($record->hari - 1));
                $record->populasi_mati      =   $request->populasi_mati ?? 0;
                $record->populasi_afkir     =   $request->populasi_afkir ?? 0;
                $record->populasi_panen     =   $request->populasi_panen ?? 0;
                $record->save();

                if ($riwayat->populasi_akhir == 0) {
                    $on_kandang         =   Setup::find($riwayat->kandang);
                    $on_kandang->status =   1;
                    $on_kandang->save();
                }
            }


            if ($request->key == 'vaksinasi') {
                $request->validate([
                    'row_id'        =>  ['required', Rule::exists('angkatan', 'id')->where('status', 1)],
                    'umur'          =>  'required',
                    'vaksin'        =>  'required',
                    'aplikasi'      =>  'required',
                ]);

                $data               =   RiwayatKandang::find($request->kandang);

                $vaksin             =   new Vaksinasi;
                $vaksin->riwayat_id =   $data->id;
                $vaksin->umur       =   $request->umur;
                $vaksin->tanggal    =   Carbon::parse($data->tanggal)->addDays(($vaksin->umur - 1));
                $vaksin->vaksin     =   $request->vaksin;
                $vaksin->aplikasi   =   $request->aplikasi;
                $vaksin->realisasi  =   $request->realisasi;
                $vaksin->save();
            }


            if ($request->key == 'timbang') {
                $data                   =   RiwayatKandang::find($request->kandang);

                $timbang                =   Timbang::where('riwayat_id', $data->id)
                    ->where('hari', $request->hari)
                    ->first() ?? new Timbang;

                $timbang->riwayat_id    =   $data->id;
                $timbang->hari          =   $request->hari;
                $timbang->tanggal       =   Carbon::parse($data->tanggal)->addDays(($request->hari - 1));

                $timbang->data_timbang  =   json_encode($request->hitung);
                $timbang->jumlah        =   $request->ekor ?? 0;
                $timbang->berat         =   $request->total ?? 0;
                $timbang->ratarata      =   $request->total > 0 ? round($request->total / $request->ekor, 2) : NULL;
                $timbang->save();
            }


            if ($request->key == 'catatan') {
                $request->validate([
                    'keterangan'    =>  'required',
                ]);

                $data                   =   RiwayatKandang::find($request->kandang);

                $catatan                =   Catatan::where('riwayat_id', $data->id)->where('hari', $request->hari)->first() ?? new Catatan;
                $catatan->riwayat_id    =   $data->id;
                $catatan->hari          =   $request->hari;
                $catatan->user_id       =   Auth::user()->id;
                $catatan->data_catatan  =   $request->keterangan;
                $catatan->save();
            }

            if ($request->key == 'kasus') {

                if ($request->check_penyakit == 'true') {
                    $penyakit   =   ['input_penyakit'  =>  'required'];
                } else {
                    $penyakit   =   ['penyakit'  =>  ['required', Rule::exists('setup', 'id')->where('slug', 'penyakit')]];
                }

                $check          =   [
                    'tanggal_penyakit'  =>  'required|date',
                ];

                $validasi       =   Validator::make($request->all(), array_merge($penyakit, $check));

                if ($validasi->fails()) {
                    $data['status']     =   400;
                    return $data;
                }

                $data                   =   RiwayatKandang::find($request->kandang);

                $kasus                  =   new Kasus;
                $kasus->riwayat_id      =   $data->id;
                $kasus->kandang         =   $data->kandang;
                $kasus->tanggal         =   $request->tanggal_penyakit;

                if ($request->check_penyakit == 'true') {
                    $penyakit           =   new Setup;
                    $penyakit->slug     =   'penyakit';
                    $penyakit->nama     =   $request->input_penyakit;
                    $penyakit->status   =   1;
                    $penyakit->save();

                    $kasus->penyakit_id =   $penyakit->id;
                } else {
                    $kasus->penyakit_id =   $request->penyakit;
                }

                $kasus->keterangan      =   $request->keterangan_penyakit;

                $files      =   $request->file('unggah_foto');
                if ($files) {
                    $name_file  =   rand() . '-' . time() . '.' . $files->getClientOriginalExtension();
                    $files->move('storage/img/kematian/', $name_file);

                    $kasus->foto        =   'storage/img/kematian/' . $name_file;
                }
                $kasus->save();

                $data['status']         =   200;
                return $data;
            }
        }
        return redirect()->route('home')->with('error', 'Anda tidak memiliki hak akses ke halaman tersebut');
    }

    public function destroy(Request $request)
    {
        if (User::setIjin('Jurnal Angkatan Ayam')) {
            if ($request->key == 'pakan') {
                $data   =   KartuStok::find($request->pakan);
                $record =   RiwayatKandang::find($data->recording_id);
                $stock  =   StokKandang::where('kandang_id', $record->kandang)
                    ->where('produk_id', $data->jenis)
                    ->get();

                $sisa   =   $data->keluar;
                foreach ($stock as $row) {
                    if ($sisa > 0) {
                        if ($row->jumlah != $row->sisa) {
                            $row->sisa  =   ($sisa > $row->jumlah) ? $row->jumlah : ($row->sisa + $sisa);
                            $row->save();

                            $sisa   =   ($sisa - $row->sisa);
                        }
                    }
                }

                return $data->delete();
            }

            if ($request->key == 'ovk') {
                $data   =   KartuStok::find($request->ovk);
                $record =   RiwayatKandang::find($data->recording_id);
                $stock  =   StokKandang::where('kandang_id', $record->kandang)
                    ->where('produk_id', $data->jenis)
                    ->get();

                $sisa   =   $data->keluar;
                foreach ($stock as $row) {
                    if ($sisa > 0) {
                        if ($row->jumlah != $row->sisa) {
                            $row->sisa  =   ($sisa > $row->jumlah) ? $row->jumlah : ($row->sisa + $sisa);
                            $row->save();

                            $sisa   =   ($sisa - $row->sisa);
                        }
                    }
                }

                return $data->delete();
            }

            if ($request->key == 'vaksinasi') {
                Vaksinasi::find($request->vaksin)->delete();
            }
        }
        return redirect()->route('home')->with('error', 'Anda tidak memiliki hak akses ke halaman tersebut');
    }
    public function excel(Request $request)
    {
        for ($i = 0; $i <= 91; $i++) {
            $tanggal = Carbon::parse($request->tgl)->addDay($i);
            $date[] = date('Y-m-d', strtotime($tanggal));
        }


        $path = $request->file('file');

        try {
            $prod_import = Excel::toArray([], $path);
        } catch (\Throwable $th) {
            return "Format Tidak didukung, ulangi lagi dengan format excel";
        }

        $resp = [];
        foreach ($prod_import[0] as $urut => $line) {

            if ($urut != 0) {
                $dt = (array)\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($line[0]);
                $dates = date('Y-m-d', strtotime($dt['date']));
                // dd(in_array($dates, $date));

                // if (in_array($dates, $date)) {
                $import = new RecordingImport;
                $import->settanggal($request->tgl);
                Excel::import($import, request()->file('file'));
                return redirect()->route('angkatanayam.index')->with('status', 'Berhasil import');
                // } else {
                //     return redirect()->route('angkatanayam.index')->with('error', 'Gagal import, tanggal tidak sama');
                // }
            }
        }
    }
    public function table(Request $request, $id)
    {
        $kandang        =   RiwayatKandang::with('produk')->find($id);
        $pakan      =   StokKandang::select(DB::raw("SUM(sisa) AS stock_sisa"), 'produk_id')
            ->where('tipe', 2)
            ->where('kandang_id', $kandang->kandang)
            ->where('sisa', '>', 0)
            ->groupBy('produk_id')
            ->get();
        $ovk        =   StokKandang::select(DB::raw("SUM(sisa) AS stock_sisa"), 'produk_id')
            ->whereIn('tipe', [1, 3])
            ->where('kandang_id', $kandang->kandang)
            ->where('sisa', '>', 0)
            ->groupBy('produk_id')
            ->get();
        return view("jurnal.angkatan.tabs.recording_table", compact('kandang', 'pakan',  'ovk'));
    }

    public function edit_record(Request $request)
    {

        if (!empty($request->mati) && $request->key = 'mati') {
            // $update = Populasi::where('id', $request->id_mati)->update(
            //     ['populasi_mati' => $request->mati]
            // );
            $riwayat                    =   RiwayatKandang::where('id', $request->kandang)
                ->where('angkatan', $request->angkatan)
                ->first();

            $record                     =   Populasi::where('hari', $request->hari_populasi)
                ->where('riwayat_id', $riwayat->id)
                ->first() ?? new Populasi;

            $record->riwayat_id         =   $riwayat->id;
            $record->kandang            =   $riwayat->kandang;
            $record->tanggal_masuk      =   $riwayat->tanggal;
            $record->hari               =   $request->hari_populasi;
            $record->tanggal_input      =   Carbon::parse($record->tanggal_masuk)->addDays(($record->hari - 1));
            $record->populasi_mati      =   $request->mati ?? 0;
            $record->save();

            if ($riwayat->populasi_akhir == 0) {
                $on_kandang         =   Setup::find($riwayat->kandang);
                $on_kandang->status =   1;
                $on_kandang->save();
            }
            return $record;
        }
        if (!empty($request->id_afkir) && $request->key == "afkir") {
            // dd($request->all());
            $riwayat                    =   RiwayatKandang::where('id', $request->kandang)
                ->where('angkatan', $request->angkatan)
                ->first();

            $record                     =   Populasi::where('hari', $request->hari_populasi)
                ->where('riwayat_id', $riwayat->id)
                ->first() ?? new Populasi;

            $record->riwayat_id         =   $riwayat->id;
            $record->kandang            =   $riwayat->kandang;
            $record->tanggal_masuk      =   $riwayat->tanggal;
            $record->hari               =   $request->hari_populasi;
            $record->tanggal_input      =   Carbon::parse($record->tanggal_masuk)->addDays(($record->hari - 1));
            $record->populasi_afkir      =   $request->afkir ?? 0;
            $record->save();

            if ($riwayat->populasi_akhir == 0) {
                $on_kandang         =   Setup::find($riwayat->kandang);
                $on_kandang->status =   1;
                $on_kandang->save();
            }
            return $record;
        }
        if ($request->key == "panen") {
            // $update = Populasi::where('id', $request->id_panen)->update(
            //     ['populasi_panen' => $request->panen]
            // );
            $riwayat                    =   RiwayatKandang::where('id', $request->kandang)
                ->where('angkatan', $request->angkatan)
                ->first();

            $record                     =   Populasi::where('hari', $request->hari_populasi)
                ->where('riwayat_id', $riwayat->id)
                ->first() ?? new Populasi;

            $record->riwayat_id         =   $riwayat->id;
            $record->kandang            =   $riwayat->kandang;
            $record->tanggal_masuk      =   $riwayat->tanggal;
            $record->hari               =   $request->hari_populasi;
            $record->tanggal_input      =   Carbon::parse($record->tanggal_masuk)->addDays(($record->hari - 1));
            $record->populasi_panen      =   $request->panen ?? 0;
            $record->save();

            if ($riwayat->populasi_akhir == 0) {
                $on_kandang         =   Setup::find($riwayat->kandang);
                $on_kandang->status =   1;
                $on_kandang->save();
            }
            return $record;
        }
        if ($request->id_datang == 'id_datang') {
            $kandang_id = RiwayatKandang::where('id', $request->kandang)->first();

            $stokan = StokKandang::where('kandang_id', $kandang_id->kandang)
                ->where('tipe', '2')
                ->where('produk_id', $request->jenis)
                ->orderBy('sisa', 'desc')
                ->take(1)->first()->sisa;
            if (!empty(DB::table('kartustok')->where('hari', $request->hari_datang)->where('tipekartu', 'pakan')->first())) {
                if (!empty(DB::table('kartustok')->where('hari', $request->hari_datang)->where('tipekartu', 'pakan')->first()->masuk)) {
                    // dd([$stokan, $request->jumlah]);
                    $update = DB::table('kartustok')->where('hari', $request->hari_datang)->where('tipekartu', 'pakan')->update(['masuk' => $request->jumlah, 'jenis' => $request->jenis, 'edited' => "sudah diedit"]);

                    // DB::table('stock_kandang')->where('kandang_id', $kandang_id->kandang)
                    //     ->where('tipe', '2')
                    //     ->where('produk_id', $request->jenis)
                    //     ->orderBy('sisa', 'desc')
                    //     ->take(1)->update(['sisa' => ($stokan + $request->jumlah_awal + $request->jumlah)]);
                    DB::table('stock_kandang')->where('kandang_id', $kandang_id->kandang)
                        ->where('tipe', '2')
                        ->where('produk_id', $request->jenis)
                        ->orderBy('sisa', 'desc')
                        // ->take(1)->update(['sisa' => ($stokan - $request->jumlah)]);
                        ->take(1)->update(['sisa' => ($stokan + $request->jumlah_awal - $request->jumlah)]);
                    $data['status']         =   200;
                    return $data;
                } else {
                    $update = DB::table('kartustok')->where('hari', $request->hari_datang)->where('tipekartu', 'pakan')->update(['masuk' => $request->jumlah, 'jenis' => $request->jenis, 'edited' => "sudah diedit"]);

                    DB::table('stock_kandang')->where('kandang_id', $kandang_id->kandang)
                        ->where('tipe', '2')
                        ->where('produk_id', $request->jenis)
                        ->orderBy('sisa', 'desc')
                        ->take(1)->update(['sisa' => ($stokan - $request->jumlah)]);
                    $data['status']         =   200;
                    return $data;
                }
            } else {
                $update                  =   new KartuStok;
                $update->tipekartu       =   'pakan';
                $update->recording_id    =   $kandang_id->id;
                $update->tanggal_masuk   =   $kandang_id->tanggal;
                $update->tanggal_kartu   =   Carbon::parse($kandang_id->tanggal)->addDays(($request->hari_datang - 1));
                $update->hari = $request->hari_datang;
                $update->masuk = $request->jumlah ?? 0;
                $update->jenis = $request->jenis ?? 0;
                $update->edited = "sudah diedit";
                $update->save();
            }
        }
        if ($request->id_keluar == 'id_keluar') {

            $request->validate([
                'keluar'        =>  ['required'],
            ]);
            $kandang_id = RiwayatKandang::where('id', $request->kandang)->first();
            $kandang = $request->kandang;
            $update = DB::table('kartustok')->where('hari', $request->hari_keluar)->where('tipekartu', 'pakan');

            if (!empty(DB::table('kartustok')->where('hari', $request->hari_keluar)->where('tipekartu', 'pakan')->first())) {
                $update->update([
                    'keluar' => $request->keluar,
                    'edited' => "sudah diedit"
                ]);
                $sisa = StokKandang::join('kartustok', 'kartustok.jenis', 'stock_kandang.produk_id')->where('kandang_id', $request->kandang)->max('sisa');
                StokKandang::join('kartustok', 'kartustok.jenis', 'stock_kandang.produk_id')->where('kandang_id', $kandang)->update(
                    [
                        'sisa' => $sisa - $request->keluar
                    ]
                );
            } else {
                $update                  =   new KartuStok;
                $update->tipekartu       =   'pakan';
                $update->recording_id    =   $kandang_id->id;
                $update->tanggal_masuk   =   $kandang_id->tanggal;
                $update->tanggal_kartu   =   Carbon::parse($kandang_id->tanggal)->addDays(($request->hari_keluar - 1));
                $update->hari = $request->hari_keluar;
                $update->keluar = $request->ovk_keluar ?? 0;
                $update->edited = "sudah diedit";
                $update->save();
            }
        }
        if ($request->key == 'ovk') {

            $kandang_id = RiwayatKandang::where('id', $request->kandang)->first();

            $stokan = StokKandang::where('kandang_id', $kandang_id->kandang)
                ->where('tipe', '1')
                ->where('produk_id', $request->jenis)
                ->orderBy('sisa', 'desc')
                ->take(1)->first()->sisa;
            if (!empty(DB::table('kartustok')->where('tipekartu', 'ovk')->where('hari', $request->hari_datang)->where('tipekartu', 'ovk')->where('edited', 'sudah diedit')->first())) {
                // dd('pertama');
                // dd($request->all());
                $update = DB::table('kartustok')->where('tipekartu', 'ovk')->where('hari', $request->hari_datang)->where('tipekartu', 'ovk')->update(
                    [
                        'masuk' => $request->jumlah,
                        'hari' => $request->hari_datang,
                        'keluar' => $request->jumlah_keluar,
                        'jenis' => $request->jenis,
                        'edited' => "sudah diedit"
                    ]
                );
                // if (!empty($request->jumlah)) {
                StokKandang::where('kandang_id', $kandang_id->kandang)
                    ->where('tipe', '1')
                    ->where('produk_id', $request->jenis)
                    ->orderBy('sisa', 'desc')
                    // ->take(1)->update(['sisa' => ($stokan + $request->jumlah)]);
                    ->take(1)->update(['sisa' => ($stokan + $request->jumlah_awal - $request->jumlah)]);
                // }
                // if (!empty($request->jumlah_keluar)) {
                //     StokKandang::where('kandang_id', $kandang_id->kandang)
                //         ->where('tipe', '1')
                //         ->where('produk_id', $request->jenis)
                //         ->orderBy('sisa', 'desc')
                //         ->take(1)->update(['sisa' => ($stokan - $request->jumlah_keluar)]);
                // }
                $data['status']         =   200;
                return $data;
            } elseif (!empty(DB::table('kartustok')->where('tipekartu', 'ovk')->where('hari', $request->hari_datang)->where('tipekartu', 'ovk')->where('edited', 'belum edit')->first())) {
                // dd('pertama');
                // dd($request->all());
                $update = DB::table('kartustok')->where('tipekartu', 'ovk')->where('hari', $request->hari_datang)->where('tipekartu', 'ovk')->update(
                    [
                        'masuk' => $request->jumlah,
                        'hari' => $request->hari_datang,
                        'keluar' => $request->jumlah_keluar,
                        'jenis' => $request->jenis,
                        'edited' => "sudah diedit"
                    ]
                );
                // if (!empty($request->jumlah)) {
                StokKandang::where('kandang_id', $kandang_id->kandang)
                    ->where('tipe', '1')
                    ->where('produk_id', $request->jenis)
                    ->orderBy('sisa', 'desc')
                    // ->take(1)->update(['sisa' => ($stokan + $request->jumlah)]);
                    ->take(1)->update(['sisa' => ($stokan + $request->jumlah_awal - $request->jumlah)]);
                // }
                // if (!empty($request->jumlah_keluar)) {
                //     StokKandang::where('kandang_id', $kandang_id->kandang)
                //         ->where('tipe', '1')
                //         ->where('produk_id', $request->jenis)
                //         ->orderBy('sisa', 'desc')
                //         ->take(1)->update(['sisa' => ($stokan - $request->jumlah_keluar)]);
                // }
                $data['status']         =   200;
                return $data;
            } else {
                // dd('cek2');
                $update                  =   new KartuStok;
                $update->tipekartu       =   'ovk';
                $update->recording_id    =   $kandang_id->id;
                $update->tanggal_masuk   =   $kandang_id->tanggal;
                $update->tanggal_kartu   =   Carbon::parse($kandang_id->tanggal)->addDays(($request->hari_datang - 1));
                $update->hari = $request->hari_datang;
                $update->masuk = $request->jumlah ?? 0;
                $update->keluar = $request->jumlah_keluar ?? 0;
                $update->jenis = $request->jenis ?? 0;
                $update->edited = "sudah diedit";
                $update->save();

                // if (!empty($request->jumlah)) {
                StokKandang::where('kandang_id', $kandang_id->kandang)
                    ->where('tipe', '1')
                    ->where('produk_id', $request->jenis)
                    ->orderBy('sisa', 'desc')
                    ->take(1)->update(['sisa' => ($stokan - $request->jumlah)]);
                // ->take(1)->update(['sisa' => ($stokan + $request->jumlah_awal - $request->jumlah)]);
                // }
                // if (!empty($request->jumlah_keluar)) {
                //     StokKandang::where('kandang_id', $kandang_id->kandang)
                //         ->where('tipe', '1')
                //         ->where('produk_id', $request->jenis)
                //         ->orderBy('sisa', 'desc')
                //         ->take(1)->update(['sisa' => ($stokan - $request->jumlah_keluar)]);
                // }
                $data['status']         =   200;
                return $data;
            }
            // else {
            //     // dd('cek3');
            //     $update                  =   new KartuStok;
            //     $update->tipekartu       =   'ovk';
            //     $update->recording_id    =   $kandang_id->id;
            //     $update->tanggal_masuk   =   $kandang_id->tanggal;
            //     $update->tanggal_kartu   =   Carbon::parse($kandang_id->tanggal)->addDays(($request->hari_datang - 1));
            //     $update->hari = $request->hari_datang;
            //     $update->masuk = $request->jumlah ?? 0;
            //     $update->masuk = $request->jumlah_keluar ?? 0;
            //     $update->jenis = $request->jenis ?? 0;
            //     $update->edited = "sudah diedit";
            //     $update->save();

            //     if (!empty($request->jumlah)) {
            //         StokKandang::where('kandang_id', $kandang_id->kandang)
            //             ->where('tipe', '1')
            //             ->where('produk_id', $request->jenis)
            //             ->orderBy('sisa', 'desc')
            //             ->take(1)->update(['sisa' => ($stokan + $request->jumlah)]);
            //         // ->take(1)->update(['sisa' => ($stokan + $request->jumlah_awal - $request->jumlah)]);
            //     }
            //     if (!empty($request->jumlah_keluar)) {
            //         StokKandang::where('kandang_id', $kandang_id->kandang)
            //             ->where('tipe', '1')
            //             ->where('produk_id', $request->jenis)
            //             ->orderBy('sisa', 'desc')
            //             ->take(1)->update(['sisa' => ($stokan - $request->jumlah_keluar)]);
            //     }
            // }

            // $update = KartuStok::where('id', $request->id_ovk)->update(['jenis' => $request->ovk]);
        }

        return response()->json([
            'update' => $update
        ]);
    }
    public function excel_timbang(Request $request)
    {
        $path = $request->file('file');

        try {
            $prod_import = Excel::toArray([], $path);
        } catch (\Throwable $th) {
            return "Format Tidak didukung, ulangi lagi dengan format excel";
        }

        $grand_total = 0;
        $data = [];
        foreach ($prod_import[0] as $urut => $line) {
            if ($urut != 0) {
                $sub_total = 0;
                foreach ($line as $l) {
                    $sub_total = $sub_total + $l;
                    if ($l > 0) {
                        $sub_ekor[] = $l; //
                    }
                }
                $grand_total = $grand_total + $sub_total;
            }
        }
        $ekor = count($sub_ekor);
        // return $ekor;
        // return $grand_total;
        $data                   =   RiwayatKandang::where('kandang', $request->kandang)->first();

        $timbang                =   Timbang::where('riwayat_id', $data->id)
            ->where('hari', $request->hari)
            ->first() ?? new Timbang;

        $timbang->riwayat_id    =   $data->id;
        $timbang->hari          =   $request->hari;
        $timbang->tanggal       =   Carbon::parse($data->tanggal)->addDays(($request->hari - 1));

        $timbang->data_timbang  =   json_encode($sub_ekor);
        $timbang->jumlah        =   $ekor ?? 0;
        $timbang->berat         =   $grand_total ?? 0;
        $timbang->ratarata      =   $grand_total > 0 ? round($grand_total / $ekor, 2) : NULL;
        $timbang->save();

        // return json_encode($resp);

        // $import = new TimbangImport;
        // $import->setAngkatan($request->angkatan);
        // $import->setKandang($request->kandang);
        // $import->setHari($request->hari);
        // Excel::import($import, request()->file('file'));
        // dd($import);

        return redirect()->route('angkatanayam.index')->with('status', 'Berhasil import');
    }
}

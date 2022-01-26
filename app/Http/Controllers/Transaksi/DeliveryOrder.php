<?php

namespace App\Http\Controllers\Transaksi;

use App\Http\Controllers\Controller;
use App\Models\Auth\User;
use App\Models\Master\CRM;
use App\Models\Master\Setup;
use App\Models\Master\Stok;
use App\Models\Transaksi\Delivery;
use App\Models\Transaksi\HeaderTrans;
use App\Models\Transaksi\ListTrans;
use App\Models\Transaksi\LogTrans;
use App\Models\Transaksi\Purchase;
use App\Models\Jurnal\Angkatan;
use App\Models\Master\Produk;
use App\Rules\Transaksi\BayarDelivery;
use App\Rules\Transaksi\JumlahDelivery;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use App\Models\Jurnal\Angkatan as ModelAngkatan;
use App\Models\Jurnal\Populasi;
use App\Models\Jurnal\RiwayatKandang;
use App\Models\Jurnal\StokKandang;
use Tanggal;

class DeliveryOrder extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        if (User::setIjin('Delivery Order')) {

            if ($request->key == 'inject') {
                foreach (Purchase::get() as $row) {
                    $data   =   json_decode($row->produk);
                    $qty    =   0;
                    $kirim  =   0;
                    foreach ($data as $val) {
                        $qty    +=  $val->jumlah;
                        $kirim  +=  $val->terkirim;
                    }
                    $row->qty       =   $qty;
                    $row->terkirim  =   $kirim;
                    $row->delivered =   ($row->qty == $row->terkirim) ? 1 : NULL;
                    $row->save();
                }
                return back();
            }

            if ($request->key == 'unduh') {
                if ($request->mulai || $request->selesai) {
                    $deli   =   Delivery::whereBetween('tanggal', [$request->mulai, $request->selesai])
                        ->orderByRaw('tanggal DESC, id DESC')
                        ->get();

                    header("Content-type: application/csv");
                    header("Content-Disposition: attachment; filename=Laporan Delivery Order Periode " . Tanggal::date($request->mulai) . " - " . Tanggal::date($request->selesai) . ".csv");
                    $fp = fopen('php://output', 'w');
                    fputcsv($fp, ["sep=,"]);

                    $data   =   [
                        "No",
                        "Tanggal Kirim",
                        "Nomor PO",
                        "Jenis",
                        "Produk",
                        "Jumlah Pengiriman",
                        "Biaya Kirim",
                        "Beban Angkut",
                        "Biaya Lain-Lain",
                        "Metode Pembayaran",
                    ];
                    fputcsv($fp, $data);

                    foreach ($deli as $i => $row) :
                        $data   =   [
                            ++$i,
                            $row->tanggal,
                            $row->purchasing->nomor_purchasing,
                            $row->produk->tipeset->nama,
                            $row->produk->nama,
                            $row->qty,
                            $row->biaya_pengiriman,
                            $row->beban_angkut,
                            $row->biaya_lain,
                            $row->kas ? $row->metode->nama : '',
                        ];
                        fputcsv($fp, $data);
                    endforeach;

                    fclose($fp);

                    // return '';
                    return view('transaksi.delivery_order.excel', compact('data', 'request'));
                } else {
                    return back()->with('error', "Pilih tanggal untuk unduh data");
                }
            } else {
                return view('transaksi.delivery_order.index');
            }
        }
        return redirect()->route('home')->with('error', 'Anda tidak memiliki hak akses ke halaman tersebut');
    }

    public function get_kandang(Request $request)
    {
        $listriwayat    =   RiwayatKandang::where('angkatan', $request->row_id)
            ->orderBy('tanggal', 'DESC')
            ->get();

        $angkatan       =   $request->row_id;

        // return response()->json([
        //     'listriwayat'=>$listriwayat,
        //     'angkatan'=>$angkatan,
        // ]);

        return view('transaksi.delivery_order.input_kandang', compact('listriwayat', 'angkatan'));
    }
    public function get_strain(Request $request)
    {
        $slug    =   Setup::where('id', $request->slug)
            ->first();
        return response()->json(['strain' => $slug]);
    }

    public function store(Request $request)
    {
        // return response()->json(['strain' => $request->all()]);
        // return response()->json(['req'=>$request->all()]);
        if (User::setIjin('Delivery Order')) {

            // dd($request->pilih_kandang2);

            $kandang    =   RiwayatKandang::find($request->pilih_kandang);
            $kandang2    =   RiwayatKandang::where('kandang', $request->pilih_kandang2);

            if ($request->check_angkatan == "on") {

                $produk =   Produk::find($request->produk);
                if ($produk) {
                    $kandang    =   Setup::where('slug', 'kandang')
                        ->where('id', $request->pilih_kandang2)
                        ->first();

                    if ($kandang) {
                        $cek_kandang    =   RiwayatKandang::whereDate('tanggal', $request->tanggal_kirim)
                            ->where('kandang', $kandang->id)
                            ->first();
                        if ($produk->tipe == 4) {
                            if ($kandang->status == 2) {

                                if (!$cek_kandang) {
                                    $data['status'] =   400;
                                    $data['msg']    =   "Kandang sudah digunakan";
                                    return $data;
                                }
                            }
                        }

                        DB::beginTransaction();

                        if ($produk->tipe == 4) {
                            $strain = Produk::where('id', $request->produk)->first()->strain;
                            // dd($strain);
                            // return response()->json(['strain' => $strain]);
                            $ang = Angkatan::orderBy('no', 'DESC')->first();
                            if (empty($ang)) {
                                $angkatan_no = 1;
                            } else {
                                $angkatan_no = Angkatan::orderBy('no', 'DESC')->first()->no + 1;
                            }
                            // return response()->json(['e' => $angkatan_no]);
                            $angkatan                       =   new Angkatan();
                            $angkatan->no                   =   $request->tulis_angkatan;
                            $angkatan->tanggal              =   $request->tanggal_kirim;
                            $angkatan->populasi_awal        =   $cek_kandang ? ($angkatan->populasi_awal + $request->jumlah_pengiriman) : $request->jumlah_pengiriman;
                            $angkatan->user_id              =   Auth::user()->id;
                            $angkatan->status               =   1;

                            if (!$angkatan->save()) {
                                DB::rollBack();
                                $data['status'] =   400;
                                $data['msg']    =   "Proses Gagal";
                                return $data;
                            }


                            $riwayat                        =   $cek_kandang ?? new RiwayatKandang;
                            $riwayat->angkatan              =   $angkatan->id;
                            $riwayat->tanggal               =   $request->tanggal_kirim;
                            $riwayat->strain_id             =   $strain;
                            $riwayat->kandang               =   $kandang->id;
                            $riwayat->populasi              =   $angkatan->populasi_awal;
                            $riwayat->status                =   1;

                            if (!$riwayat->save()) {
                                DB::rollBack();
                                $data['status'] =   400;
                                $data['msg']    =   "Proses Gagal";
                                return $data;
                            }

                            $kandang->status        =   2;
                            if (!$kandang->save()) {
                                DB::rollBack();
                                $data['status'] =   400;
                                $data['msg']    =   "Proses Gagal";
                                return $data;
                            }
                        }


                        $stock  =   Stok::where('produk_id', $produk->id)->get();

                        $sisa   =   $request->jumlah_pengiriman;
                        foreach ($stock as $row) {
                            if ($sisa > 0) {
                                if ($sisa >= $row->stock_opname) {
                                    $ambil  =   $row->stock_opname;
                                } else {
                                    $ambil  =   $sisa;
                                }
                                $sisa   =   ($sisa - $ambil);

                                $row->stock_opname  =   $row->stock_opname - $ambil;
                                if (!$row->save()) {
                                    DB::rollBack();
                                    $data['status'] =   400;
                                    $data['msg']    =   "Proses Gagal";
                                    return $data;
                                }

                                if ($produk->tipe != 4) {
                                    $stockkandang               =   new StokKandang();
                                    $stockkandang->user_id      =   Auth::user()->id;
                                    $stockkandang->stock_id     =   $row->id;
                                    $stockkandang->tanggal      =   $request->tanggal_kirim;
                                    $stockkandang->produk_id    =   $produk->id;
                                    $stockkandang->tipe         =   $row->tipe;
                                    $stockkandang->jumlah       =   $ambil;
                                    $stockkandang->sisa         =   $ambil;
                                    $stockkandang->kandang_id   =   $kandang->id;
                                    if (!$stockkandang->save()) {
                                        DB::rollBack();
                                        $data['status'] =   400;
                                        $data['msg']    =   "Proses Gagal";
                                        return $data;
                                    }
                                }
                            }
                        }

                        DB::commit();
                    } else {
                        $data['status'] =   400;
                        $data['msg']    =   "Kandang tidak tersedia";
                        return $data;
                    }
                } else {
                    $data['status'] =   400;
                    $data['msg']    =   "Produk belum dipilih";
                    return $data;
                }
            }

            if (!$request->tanggal_kirim) {
                $report['status']   =   400;
                $report['message']  =   'Pilih tanggal mutasi';
                return $report;
            } else {
                if (!empty($request->pilih_kandang)) {
                    $kandangs = $kandang->first();
                } elseif (!empty($request->pilih_kandang2)) {
                    $kandangs = $kandang2->first();
                }
                if ($request->jumlah_mutasi > $kandangs->populasi) {
                    $report['status']   =   400;
                    $report['message']  =   'Jumlah mutasi melebihi ayam tersedia';
                    return $report;
                } else {
                    // dd($request->all());
                    $farm   =   Setup::where('id', $kandangs->kandang)->first();
                    // dd($farm);
                    // if ($farm->status == 2) {
                    // $report['status']   =   400;
                    // $report['message']  =   'Kandang sudah digunakan';
                    // return $report;
                    // } else {

                    DB::beginTransaction();


                    $hari                       =   date_diff(date_create($request->tanggal_kirim), date_create($kandangs->tanggal));
                    $record                     =   Populasi::where('hari', $hari->d)
                        ->where('riwayat_id', $kandangs->id)
                        ->first() ?? new Populasi;
                    $record->riwayat_id         =   $kandangs->id;
                    $record->kandang            =   $kandangs->kandang;
                    $record->tanggal_masuk      =   $kandangs->tanggal_kirim;
                    $record->hari               =   $hari->d + 1;
                    $record->tanggal_input      =   $request->tanggal_kirim;
                    $record->populasi_mati      =   $record->populasi_mati ?? 0;
                    $record->populasi_afkir     =   $record->populasi_afkir ?? 0;
                    $record->populasi_panen     =   $record->populasi_panen + $request->jumlah_mutasi;
                    if (!$record->save()) {
                        DB::rollBack();
                        $report['status']   =   400;
                        $report['message']  =   'Proses mutasi gagal';
                        return $report;
                    }


                    // dd([$kandangs->kandang, $kandangs->strain_id, $request->angkatan]);
                    $riwayat                     =   RiwayatKandang::where('angkatan', $request->angkatan)
                        ->where('kandang', $kandangs->kandang)
                        ->where('strain_id', $kandangs->strain_id)
                        ->first() ?? new RiwayatKandang;
                    // dd($riwayat);
                    $riwayat->angkatan              =   $request->angkatan;
                    $riwayat->strain_id             =   $kandangs->strain_id;
                    $riwayat->tanggal               =   $kandangs->tanggal_kirim;
                    $riwayat->kandang               =   $farm->id;
                    $riwayat->populasi              =   $riwayat->populasi + $request->jumlah_pengiriman;
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

                    if ($kandangs->populasi_akhir == 0) {
                        $on_kandang         =   Setup::find($kandangs->kandang);
                        $on_kandang->status =   1;
                        $on_kandang->save();

                        $kandangs->status    =   2;
                        $kandangs->save();
                    }
                    // }
                }
            }

            $purchase               =   Purchase::find($request->nomor);

            DB::beginTransaction();

            $delivery                   =   new Delivery;
            $delivery->purchase_id      =   $purchase->id;
            $delivery->produk_id        =   $request->produk;
            $delivery->tanggal          =   $request->tanggal_kirim;
            $delivery->qty              =   $request->jumlah_pengiriman;

            if ($request->check_kas) {
                $payment                =   new Setup;
                $payment->slug          =   'payment';
                $payment->nama          =   $request->tulis_pembayaran;
                $payment->status        =   2;
                if (!$payment->save()) {
                    DB::rollBack();
                    $result['status']   =   400;
                    $result['msg']      =   "Proses gagal";
                    return $result;
                }

                $delivery->kas          =   $payment->id;
            } else {
                $delivery->kas          =   $request->metode_pembayaran;
            }

            $delivery->biaya_pengiriman =   $request->biaya_pengiriman;
            $delivery->beban_angkut     =   $request->beban_angkut;
            $delivery->biaya_lain       =   $request->biaya_lain_lain;
            $delivery->user_id          =   Auth::user()->id;

            $item   =   [];
            foreach (json_decode($purchase->produk) as $row) {
                if ($row->id == $request->nomor_purchase) {
                    if ($delivery->qty > ($row->jumlah - $row->terkirim)) {
                        DB::rollBack();
                        $result['status']   =   400;
                        $result['msg']      =   "Jumlah pengiriman melebihi PO";
                        return $result;
                    }
                    $item[]   =   [
                        'id'        =>  rand(),
                        'produk'    =>  $row->produk,
                        'harga'     =>  $row->harga,
                        'jumlah'    =>  $row->jumlah,
                        'terkirim'  => ($row->terkirim + $delivery->qty),
                    ];
                } else {
                    $item[] =   $row;
                }
            }

            $purchase->terkirim     =   $purchase->terkirim + $delivery->qty;
            $purchase->delivered    =   ($purchase->qty == $purchase->terkirim) ? 1 : NULL;
            $purchase->produk       =   json_encode($item);
            if (!$purchase->save()) {
                DB::rollBack();
                $result['status']   =   400;
                $result['msg']      =   "Proses gagal";
                return $result;
            }


            if (!$delivery->save()) {
                DB::rollBack();
                $result['status']   =   400;
                $result['msg']      =   "Proses gagal";
                return $result;
            }

            $stock                      =   new Stok;
            $stock->produk_id           =   $request->produk;
            $stock->qty_awal            =   $delivery->qty;
            $stock->stock_opname        =   $delivery->qty;
            $stock->delivery_id         =   $delivery->id;
            $stock->tanggal             =   $delivery->tanggal;
            $stock->tipe                =   Produk::find($stock->produk_id)->tipe;
            if (!$stock->save()) {
                DB::rollBack();
                $result['status']   =   400;
                $result['msg']      =   "Proses gagal";
                return $result;
            }

            if ($request->biaya_pengiriman) {
                $log                =   new LogTrans;
                $log->table         =   'delivery';
                $log->table_id      =   $delivery->id;
                $log->tanggal       =   $delivery->tanggal;
                $log->jenis         =   'biaya_kirim';
                $log->kas           =   $delivery->kas;
                $log->nominal       =   $request->biaya_pengiriman;
                $log->status        =   1;
                if (!$log->save()) {
                    DB::rollBack();
                    $result['status']   =   400;
                    $result['msg']      =   "Proses gagal";
                    return $result;
                }
            }

            if ($request->beban_angkut) {
                $log                =   new LogTrans;
                $log->table         =   'delivery';
                $log->table_id      =   $delivery->id;
                $log->tanggal       =   $delivery->tanggal;
                $log->jenis         =   'beban_angkut';
                $log->kas           =   $delivery->kas;
                $log->nominal       =   $request->beban_angkut;
                $log->status        =   1;
                if (!$log->save()) {
                    DB::rollBack();
                    $result['status']   =   400;
                    $result['msg']      =   "Proses gagal";
                    return $result;
                }
            }

            if ($request->biaya_lain_lain) {
                $log                =   new LogTrans;
                $log->table         =   'delivery';
                $log->table_id      =   $delivery->id;
                $log->tanggal       =   $delivery->tanggal;
                $log->jenis         =   'biaya_lain_lain';
                $log->kas           =   $delivery->kas;
                $log->nominal       =   $request->biaya_lain_lain;
                $log->status        =   1;
                if (!$log->save()) {
                    DB::rollBack();
                    $result['status']   =   400;
                    $result['msg']      =   "Proses gagal";
                    return $result;
                }
            }

            DB::commit();
        }
        return redirect()->route('home')->with('error', 'Anda tidak memiliki hak akses ke halaman tersebut');
    }

    public function update(Request $request)
    {
        if (User::setIjin('Delivery Order')) {
            $deliv  =   Delivery::find($request->row_id);

            if ($deliv) {

                DB::beginTransaction();

                $purchase   =   Purchase::find($deliv->purchase_id);
                $item       =   [];
                foreach (json_decode($purchase->produk) as $row) {
                    if ($row->produk == $deliv->produk_id) {
                        $item[]   =   [
                            'id'        =>  rand(),
                            'produk'    =>  $row->produk,
                            'harga'     =>  $row->harga,
                            'jumlah'    =>  $row->jumlah,
                            'terkirim'  => (($row->terkirim - $deliv->qty) + $request->jumlah_kirim),
                        ];
                    } else {
                        $item[] =   $row;
                    }
                }

                $purchase->terkirim         =   ($purchase->terkirim - $deliv->qty) + $request->jumlah_kirim;
                $purchase->delivered        =   ($purchase->qty == $purchase->terkirim) ? 1 : NULL;
                $purchase->produk           =   json_encode($item);
                if (!$purchase->save()) {
                    DB::rollBack();
                }


                $deliv->tanggal             =   $request->tanggal;
                $deliv->qty                 =   $request->jumlah_kirim;
                $deliv->kas                 =   ($request->biaya_kirim or $request->biaya_beban_angkut) ? $request->metode_bayar : NULL;
                $deliv->biaya_pengiriman    =   $request->biaya_kirim;
                $deliv->beban_angkut        =   $request->biaya_beban_angkut;
                $deliv->biaya_lain          =   $request->biaya_lain;
                $deliv->user_id             =   Auth::user()->id;
                if (!$deliv->save()) {
                    DB::rollBack();
                }

                $stock                      =   Stok::where('delivery_id', $deliv->id)->first();
                $stock->qty_awal            =   $deliv->qty;
                $stock->stock_opname        =   $deliv->qty;
                $stock->tanggal             =   $deliv->tanggal;
                if (!$stock->save()) {
                    DB::rollBack();
                }

                $biaya                      =   LogTrans::where('jenis', 'biaya_kirim')
                    ->where('table', 'delivery')
                    ->whereIn('status', [1, 2])
                    ->where('table_id', $deliv->id)
                    ->first();

                if ($request->biaya_kirim) {
                    if ($biaya) {
                        if ($biaya->status != 2) {
                            $biaya->tanggal     =   $deliv->tanggal;
                            $biaya->kas         =   $request->metode_bayar;
                            $biaya->nominal     =   $request->biaya_kirim;
                            if (!$biaya->save()) {
                                DB::rollBack();
                            }
                        }
                    } else {
                        $biaya              =   new LogTrans;
                        $biaya->table       =   'delivery';
                        $biaya->table_id    =   $deliv->id;
                        $biaya->tanggal     =   $deliv->tanggal;
                        $biaya->kas         =   $request->metode_bayar;
                        $biaya->jenis       =   'biaya_kirim';
                        $biaya->nominal     =   $request->biaya_kirim;
                        $biaya->status      =   1;
                        if (!$biaya->save()) {
                            DB::rollBack();
                        }
                    }
                } else {
                    $biaya ? $biaya->delete() : '';
                }

                $beban                      =   LogTrans::where('jenis', 'beban_angkut')
                    ->where('table', 'delivery')
                    ->whereIn('status', [1, 2])
                    ->where('table_id', $deliv->id)
                    ->first();

                if ($request->biaya_beban_angkut) {
                    if ($beban) {
                        if ($beban->status != 2) {
                            $beban->tanggal     =   $deliv->tanggal;
                            $beban->kas         =   $request->metode_bayar;
                            $beban->nominal     =   $request->biaya_beban_angkut;
                            if (!$beban->save()) {
                                DB::rollBack();
                            }
                        }
                    } else {
                        $beban              =   new LogTrans;
                        $beban->table       =   'delivery';
                        $beban->table_id    =   $deliv->id;
                        $beban->tanggal     =   $deliv->tanggal;
                        $beban->kas         =   $request->metode_bayar;
                        $beban->jenis       =   'beban_angkut';
                        $beban->nominal     =   $request->biaya_beban_angkut;
                        $beban->status      =   1;
                        if (!$beban->save()) {
                            DB::rollBack();
                        }
                    }
                } else {
                    $beban ? $beban->delete() : '';
                }

                $biaya_lain                 =   LogTrans::where('jenis', 'biaya_lain_lain')
                    ->where('table', 'delivery')
                    ->whereIn('status', [1, 2])
                    ->where('table_id', $deliv->id)
                    ->first();

                if ($request->biaya_lain) {
                    if ($biaya_lain) {
                        if ($biaya_lain->status != 2) {
                            $biaya_lain->tanggal     =   $deliv->tanggal;
                            $biaya_lain->kas         =   $request->metode_bayar;
                            $biaya_lain->nominal     =   $request->biaya_lain;
                            if (!$biaya_lain->save()) {
                                DB::rollBack();
                            }
                        }
                    } else {
                        $biaya_lain              =   new LogTrans;
                        $biaya_lain->table       =   'delivery';
                        $biaya_lain->table_id    =   $deliv->id;
                        $biaya_lain->tanggal     =   $deliv->tanggal;
                        $biaya_lain->kas         =   $request->metode_bayar;
                        $biaya_lain->jenis       =   'biaya_lain_lain';
                        $biaya_lain->nominal     =   $request->biaya_lain;
                        $biaya_lain->status      =   1;
                        if (!$biaya_lain->save()) {
                            DB::rollBack();
                        }
                    }
                } else {
                    $biaya_lain ? $biaya_lain->delete() : '';
                }

                DB::commit();
            }
        }
        return redirect()->route('home')->with('error', 'Anda tidak memiliki hak akses ke halaman tersebut');
    }

    public function purchase()
    {
        if (User::setIjin('Delivery Order')) {
            $purchase   =   Purchase::where('delivered', NULL)
                ->orderByRaw('id DESC, tanggal ASC')
                ->paginate(10);
            // dd($purchase);
            return view('transaksi.delivery_order.purchase', compact('purchase'));
        }
        return redirect()->route('home')->with('error', 'Anda tidak memiliki hak akses ke halaman tersebut');
    }
    public function produk_cek(Request $request)
    {
        $id_tipe = $request->produk;
        $tipe = Produk::where('id', $id_tipe)->pluck('tipe')->first();
        return response()->json(['tipe' => $tipe]);
    }

    public function search(Request $request)
    {
        if (User::setIjin('Delivery Order')) {
            $delivery   =   Delivery::orderByRaw('delivery.tanggal DESC, delivery.id DESC')
            ->join('produk','produk.id','delivery.produk_id')
            ->where('nama','like','%'.$request->key.'%')->paginate(10);

            $payment    =   Setup::where('slug', 'payment')
                ->pluck('nama', 'id');

            return view('transaksi.delivery_order.daftar', compact('delivery', 'payment'));
        }
        return redirect()->route('home')->with('error', 'Anda tidak memiliki hak akses ke halaman tersebut');
    }
    public function daftar(Request $request)
    {
        if (User::setIjin('Delivery Order')) {
            $delivery   =   Delivery::orderByRaw('delivery.tanggal DESC, delivery.id DESC')->paginate(10);

            $payment    =   Setup::where('slug', 'payment')
                ->pluck('nama', 'id');

            return view('transaksi.delivery_order.daftar', compact('delivery', 'payment'));
        }
        return redirect()->route('home')->with('error', 'Anda tidak memiliki hak akses ke halaman tersebut');
    }

    public function input()
    {
        if (User::setIjin('Delivery Order')) {
            $data   =   ModelAngkatan::where('status', 1)
                ->orderBy('no', 'ASC')
                ->get();
            $payment    =   Setup::where('slug', 'payment')
                ->pluck('nama', 'id');

            $kandang = Setup::where('slug', 'kandang')->get();

            return view('transaksi.delivery_order.diterima', compact('payment', 'data', 'kandang'));
        }
        return redirect()->route('home')->with('error', 'Anda tidak memiliki hak akses ke halaman tersebut');
    }
}

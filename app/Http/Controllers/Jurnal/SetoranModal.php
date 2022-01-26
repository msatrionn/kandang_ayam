<?php

namespace App\Http\Controllers\Jurnal;

use App\Http\Controllers\Controller;
use App\Models\Auth\User;
use App\Models\Master\Setup;
use App\Models\Transaksi\HeaderTrans;
use App\Models\Transaksi\ListTrans;
use App\Rules\Jurnal\InputModal;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use Tanggal;

class SetoranModal extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        if (User::setIjin('Setoran Modal')) {
            if ($request->key == 'unduh') {
                header("Content-type: application/csv");
                header("Content-Disposition: attachment; filename=Jurnal Setoran Modal " . Tanggal::date($request->mulai) . " - " . Tanggal::date($request->selesai) . ".csv");
                $fp = fopen('php://output', 'w');
                fputcsv($fp, ["sep=,"]);

                $data   =   [
                    "No",
                    "Tanggal",
                    "Jenis Modal",
                    "Kas",
                    "Nominal",
                    "Keterangan",
                ];
                fputcsv($fp, $data);

                $setor  =   HeaderTrans::whereIn('jenis', ['setor_modal', 'tarik_modal'])
                            ->whereBetween('tanggal', [$request->mulai, $request->selesai])
                            ->orderByRaw('id DESC, tanggal DESC')
                            ->get();

                foreach ($setor as $i => $row) {
                    $data   =   [
                        ++$i,
                        $row->tanggal,
                        $row->jenis,
                        $row->method->nama,
                        $row->total_trans,
                        $row->keterangan,
                    ];
                    fputcsv($fp, $data);
                }

                fclose($fp);
                return '';
            } else {
                $payment    =   Setup::where('slug', 'payment')
                                ->pluck('nama', 'id');

                $data       =   HeaderTrans::whereIn('jenis', ['setor_modal', 'tarik_modal'])
                                ->orderByRaw('id DESC, tanggal DESC')
                                ->paginate(20);

                return view('jurnal.setoran_modal.index', compact('payment', 'data'));
            }
        }
        return redirect()->route('home')->with('error', 'Anda tidak memiliki hak akses ke halaman tersebut');
    }

    public function store(Request $request)
    {
        if (User::setIjin('Setoran Modal')) {
            $cek_valid  =   [
                'tanggal'               =>  'required|date',
                'jenis_modal'           =>  'required|in:in,out',
                'nominal_setor_modal'   =>  ['required', new InputModal([$request->setoran_kas, $request->jenis_modal])],
                'keterangan'            =>  'required',
            ];

            if ($request->check_kas) {
                $setoran    =   [
                    "tulis_kas"         =>  'required|string'
                ];
            } else {
                $setoran    =   [
                    'setoran_kas'       =>  ['required', Rule::exists('setup', 'id')->where('slug', 'payment')],
                ];
            }

            $validasi   =   Validator::make($request->all(), array_merge($cek_valid, $setoran));

            if ($validasi->fails()) {
                return back()->withErrors($validasi)->withInput();
            }

            DB::beginTransaction();

            $modal                  =   new HeaderTrans;
            $modal->jenis           =   $request->jenis_modal == 'in' ? 'setor_modal' : 'tarik_modal';
            $modal->nomor           =   HeaderTrans::ambil_nomor($modal->jenis);
            $modal->user_id         =   Auth::user()->id;
            $modal->tanggal         =   $request->tanggal;
            $modal->total_trans     =   $request->nominal_setor_modal;
            $modal->payment         =   $request->nominal_setor_modal;

            if ($request->check_kas) {
                $payment            =   new Setup;
                $payment->slug      =   'payment';
                $payment->nama      =   $request->tulis_kas;
                $payment->status    =   2;
                if (!$payment->save()) {
                    DB::rollBack() ;
                }

                $modal->payment_method  =   $payment->id;
            } else {
                $modal->payment_method  =   $request->setoran_kas;
            }

            $modal->keterangan      =   $request->keterangan;
            $modal->status          =   1;
            if (!$modal->save()) {
                DB::rollBack();
            }

            $list                  =   new ListTrans;
            $list->header_id       =   $modal->id ;
            $list->type            =   $modal->jenis ;
            $list->harga_satuan    =   $request->nominal_setor_modal;
            $list->total_harga     =   $request->nominal_setor_modal;
            if (!$list->save()) {
                DB::rollBack();
            }

            DB::commit();

            return back()->with('status', 'Input modal berhasil dilakukan');
        }
        return redirect()->route('home')->with('error', 'Anda tidak memiliki hak akses ke halaman tersebut');
    }
}

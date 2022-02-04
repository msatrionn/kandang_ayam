<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Models\Master\Setup;
use App\Models\Transaksi\HeaderTrans;
use App\Models\Transaksi\ListTrans;
use App\Models\Transaksi\LogTrans;
use App\Models\Transaksi\Purchase;
use Illuminate\Http\Request;

class ReportPengeluaran extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->key == 'doc') {
            // $doc = ListTrans::with('headtrans')->where('type', 'jual_ayam')->get();
            $doc = ListTrans::with('headtrans')
                ->where('type', 'jual_ayam')->get();
            // dd(Setup::where('id', $item->product_id)->first()->nama);
            // with('listtrans')->get();
            return view('report.pengeluaran.doc', compact('doc'));
        } elseif ($request->key == 'pakan') {
            return view('report.pengeluaran.pakan');
        } elseif ($request->key == 'ovk') {
            return view('report.pengeluaran.ovk');
        } elseif ($request->key == 'pemanas') {
            // dd(LogTrans::with('angkatan')->get());
            return view('report.pengeluaran.pengeluaran_pemanas');
        } elseif ($request->key == 'tk') {
            return view('report.pengeluaran.pengeluaran_tk');
        } elseif ($request->key == 'listrik') {
            return view('report.pengeluaran.listrik');
        } elseif ($request->key == 'penyusutan') {
            return view('report.pengeluaran.sewa_kandang');
        } elseif ($request->key == 'transport') {
            return view('report.pengeluaran.transport');
        } elseif ($request->key == 'humas') {
            return view('report.pengeluaran.sumbangan');
        } elseif ($request->key == 'operasional') {
            return view('report.pengeluaran.operasional');
        }
        $data   =   Purchase::paginate(10);
        return view('report.pengeluaran.index', compact('data'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}

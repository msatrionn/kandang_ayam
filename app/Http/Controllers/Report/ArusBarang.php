<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Models\Auth\User;
use App\Models\Jurnal\Angkatan;
use Illuminate\Http\Request;

class ArusBarang extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        if (User::setIjin('Report Arus Barang')) {
            $angkatan   =   Angkatan::orderBy('no', 'ASC')->get();
            return view('report.arusbarang.index', compact('angkatan'));
        }
        return redirect()->route('home')->with('error', 'Anda tidak memiliki hak akses ke halaman tersebut');
    }
}

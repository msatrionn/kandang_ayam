<?php

namespace App\Models\Transaksi;

use App\Models\Auth\User;
use App\Models\Master\CRM;
use App\Models\Master\Setup;
use Illuminate\Database\Eloquent\Model;
use App\Models\Transaksi\Purchase;

class HeaderTrans extends Model
{
    protected $table    =   'trans_header';
    protected $appends  =   ['nomor_transaksi', 'nominal_transaksi', 'perubahan_transaksi', 'relasi_perubahan'];

    public static function ambil_nomor($jenis)
    {
        $data   =   HeaderTrans::select('nomor')
            ->where('jenis', $jenis)
            ->whereYear('tanggal', date('Y'))
            ->orderBy('nomor', 'DESC')
            ->first();

        return $data ? $data->nomor + 1 : 1;
    }

    public static function jml_trans_head($id)
    {
        return  ListTrans::where('header_id', $id)
            ->sum('qty');
    }

    public function getRelasiPerubahanAttribute()
    {
        $data   =   HeaderTrans::where('adj', $this->id)->first();

        if ($data) {
            if (($data->jenis == 'pengeluaran') || ($data->jenis == 'beban')) {
                return 'PAY-' . date('Ym', strtotime($data->tanggal)) . str_pad((string)$data->nomor, 4, "0", STR_PAD_LEFT);
            }
            if ($data->jenis == 'penjualan_ayam') {
                return 'INV-' . date('Ym', strtotime($data->tanggal)) . str_pad((string)$data->nomor, 4, "0", STR_PAD_LEFT);
            }
            if ($data->jenis == 'penjualan_lain') {
                return 'PL-' . date('Ym', strtotime($data->tanggal)) . str_pad((string)$data->nomor, 4, "0", STR_PAD_LEFT);
            }
        } else {
            return FALSE;
        }
    }

    public static function getData($tgl, $cari)
    {
        return HeaderTrans::where('jenis', 'pengeluaran_lain')
            ->orderByRaw('id DESC, tanggal DESC')
            ->where('status', 1)
            ->orwhere('keterangan', 'like', '%' . $cari . '%')
            ->orwhere('payment', 'like', '%' . $cari . '%')
            ->get();
    }
    // public static function getDataProduk($cari)
    // {
    //     return HeaderTrans::where('jenis', 'pengeluaran_lain')
    //         ->orderByRaw('id DESC, tanggal DESC')
    //         ->where('status', 1)
    //         ->orwhere('keterangan', 'like', '%' . $cari . '%')
    //         ->orwhere('payment', 'like', '%' . $cari . '%')
    //         ->get();
    // }

    public function getNomorTransaksiAttribute()
    {
        if (($this->jenis == 'pengeluaran') || ($this->jenis == 'beban')) {
            return 'PAY-' . date('Ym', strtotime($this->tanggal)) . str_pad((string)$this->nomor, 4, "0", STR_PAD_LEFT);
        }
        if ($this->jenis == 'penjualan_ayam') {
            return 'INV-' . date('Ym', strtotime($this->tanggal)) . str_pad((string)$this->nomor, 4, "0", STR_PAD_LEFT);
        }
        if ($this->jenis == 'penjualan_lain') {
            return 'PL-' . date('Ym', strtotime($this->tanggal)) . str_pad((string)$this->nomor, 4, "0", STR_PAD_LEFT);
        }
    }

    public function getPerubahanTransaksiAttribute()
    {
        $data   =   HeaderTrans::find($this->adj);

        if ($data) {
            if (($data->jenis == 'pengeluaran') || ($data->jenis == 'beban')) {
                return 'PAY-' . date('Ym', strtotime($data->tanggal)) . str_pad((string)$data->nomor, 4, "0", STR_PAD_LEFT);
            }
            if ($data->jenis == 'penjualan_ayam') {
                return 'INV-' . date('Ym', strtotime($data->tanggal)) . str_pad((string)$data->nomor, 4, "0", STR_PAD_LEFT);
            }
            if ($data->jenis == 'penjualan_lain') {
                return 'PL-' . date('Ym', strtotime($data->tanggal)) . str_pad((string)$data->nomor, 4, "0", STR_PAD_LEFT);
            }
        } else {
            return FALSE;
        }
    }

    public function getNominalTransaksiAttribute()
    {
        if (($this->jenis == 'pengeluaran') or ($this->jenis == 'beban') or ($this->jenis == 'tarik_modal')) {
            return "(" . number_format($this->total_trans, 2) . ")";
        } else {
            return number_format($this->total_trans, 2);
        }
    }

    public function purchase()
    {
        return $this->belongsTo(Purchase::class, 'purchase_id', 'id');
    }

    public function method()
    {
        return $this->belongsTo(Setup::class, 'payment_method', 'id');
    }

    public function konsumen()
    {
        return $this->belongsTo(CRM::class, 'konsumen_id', 'id');
    }

    public function list_trans()
    {
        return $this->hasMany(ListTrans::class, 'header_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function child()
    {
        return $this->belongsTo(HeaderTrans::class, 'id', 'parent');
    }

    public function kandang()
    {
        return $this->belongsTo(Setup::class, 'kandang_id', 'id');
    }
}

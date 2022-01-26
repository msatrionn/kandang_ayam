<?php

namespace App\Models\Transaksi;

use App\Models\Jurnal\RiwayatKandang;
use App\Models\Master\Produk;
use App\Models\Master\Setup;
use App\Models\Master\Stok;
use Illuminate\Database\Eloquent\Model;

class ListTrans extends Model
{
    protected $table = 'trans_list';

    public function stok()
    {
        return $this->belongsTo(Stok::class, 'stok_id', 'id');
    }

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'product_id', 'id');
    }

    public function riwayat()
    {
        return $this->belongsTo(RiwayatKandang::class, 'stok_id', 'id');
    }

    public function kandang()
    {
        return $this->belongsTo(Setup::class, 'kandang_id', 'id');
    }
}

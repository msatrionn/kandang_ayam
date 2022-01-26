<?php

namespace App\Models\Jurnal;

use App\Models\Master\Produk;
use App\Models\Master\Setup;
use App\Models\Master\Stok;
use Illuminate\Database\Eloquent\Model;

class StokKandang extends Model
{
    protected $table = 'stock_kandang';
    protected $guarded = [];

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'produk_id', 'id');
    }

    public function stok()
    {
        return $this->belongsTo(Stok::class, 'stock_id', 'id');
    }

    public function kandang()
    {
        return $this->belongsTo(Setup::class, 'kandang_id', 'id')->withTrashed();
    }
}

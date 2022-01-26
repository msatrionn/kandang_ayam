<?php

namespace App\Models\Jurnal;

use App\Models\Master\Produk;
use Illuminate\Database\Eloquent\Model;

class Vaksinasi extends Model
{
    protected $table = 'vaksinasi';
    protected $guarded = [];

    public function stok()
    {
        return $this->belongsTo(Produk::class, 'vaksin', 'id');
    }
}

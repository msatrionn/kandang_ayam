<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Model;
use App\Models\Master\Produk;
use App\Models\Transaksi\Delivery;
use App\Models\Master\Setup;

class Stok extends Model
{
    protected $table = 'stock';

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'produk_id', 'id');
    }

    public function delivery()
    {
        return $this->belongsTo(Delivery::class, 'delivery_id', 'id');
    }

    public function tipeset()
    {
        return $this->belongsTo(Setup::class, 'tipe', 'id')->withTrashed();
    }
}

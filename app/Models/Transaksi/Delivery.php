<?php

namespace App\Models\Transaksi;

use App\Models\Master\Produk;
use App\Models\Master\Setup;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Transaksi\Purchase;

class Delivery extends Model
{
    use SoftDeletes;
    protected $table = 'delivery';

    public function purchasing()
    {
        return $this->belongsTo(Purchase::class, 'purchase_id', 'id');
    }

    public static function insert_angkatan()
    {
        $data   =   Delivery::select('angkatan')
                    ->whereIn('purchase_id', Purchase::select('id')->where('tipe', 4))
                    ->orderBy('angkatan', 'DESC')
                    ->limit(1)
                    ->first();

        return $data ? ($data->angkatan + 1) : 1 ;
    }

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'produk_id', 'id');
    }

    public function metode()
    {
        return $this->belongsTo(Setup::class, 'kas', 'id');
    }
}

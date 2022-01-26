<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Master\Produk;

class Supplier extends Model
{
    use SoftDeletes;
    protected $table = 'supplier';

    public function relateproduk()
    {
        return $this->hasMany(Produk::class, 'supplier_id', 'id');
    }
}

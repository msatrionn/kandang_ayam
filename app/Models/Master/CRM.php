<?php

namespace App\Models\Master;

use App\Models\Transaksi\HeaderTrans;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CRM extends Model
{
    use SoftDeletes;
    protected $table = 'konsumen';

    public function listtrans()
    {
        return $this->hasMany(HeaderTrans::class, 'konsumen_id', 'id');
    }
}

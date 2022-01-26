<?php

namespace App\Models\Master;

use App\Models\Jurnal\Bon;
use App\Models\Transaksi\Gaji;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Karyawan extends Model
{
    use SoftDeletes;
    protected $table    =   'karyawan';
    protected $appends  =   ['total_cashbon'];

    public function listgaji()
    {
        return $this->hasMany(Gaji::class, 'id', 'karyawan_id');
    }

    public function getTotalCashbonAttribute()
    {
        $bayar  =   Gaji::where('karyawan_id', $this->id)
                    ->sum('cashbon') ;

        $bon    =   Bon::where('karyawan_id', $this->id)
                    ->sum('nominal') ;

        return $bon - $bayar ;
    }
}

<?php

namespace App\Models\Jurnal;

use App\Models\Master\Karyawan;
use App\Models\Master\Setup;
use Illuminate\Database\Eloquent\Model;


class Bon extends Model
{
    protected $table    =   'cashbon';

    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class, 'karyawan_id', 'id');
    }

    public function setup()
    {
        return $this->belongsTo(Setup::class, 'payment_id', 'id');
    }
}

<?php

namespace App\Models\Transaksi;

use App\Models\Auth\User;
use App\Models\Master\Setup;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Gaji extends Model
{
    use SoftDeletes;
    protected $table = 'gaji';

    public function karyawan()
    {
        return $this->belongsTo(User::class, 'karyawan_id', 'id') ;
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function pay()
    {
        return $this->belongsTo(Setup::class, 'metode_kas', 'id');
    }
}

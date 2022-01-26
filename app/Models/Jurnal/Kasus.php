<?php

namespace App\Models\Jurnal;

use App\Models\Master\Setup;
use Illuminate\Database\Eloquent\Model;

class Kasus extends Model
{
    protected $table = 'kasus';

    public function penyakit()
    {
        return $this->belongsTo(Setup::class, 'penyakit_id', 'id') ;
    }
}

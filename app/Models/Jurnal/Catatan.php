<?php

namespace App\Models\Jurnal;

use Illuminate\Database\Eloquent\Model;
use App\Models\Auth\User;

class Catatan extends Model
{
    protected $table = 'catatan';
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}

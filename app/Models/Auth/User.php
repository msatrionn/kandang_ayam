<?php

namespace App\Models\Auth;

use App\Models\Master\Setup;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class User extends Authenticatable
{
    use Notifiable, SoftDeletes;
    protected $fillable     = [ 'name', 'email', 'password' ];
    protected $hidden       = [ 'password', 'remember_token' ];
    protected $casts        = [ 'email_verified_at' => 'datetime' ];

    public static function setIjin($value)
    {
        $permission = false;
        if (Auth::user()->type == 'admin') {
            $permission = TRUE;
        } else {
            foreach (explode(',', Auth::user()->permission) as $item) {
                $check  =   Setup::where('id', $item)
                            ->where('slug', 'permission')
                            ->where('status', 1)
                            ->first();

                if ($check) {
                    if ($check->id == Setup::where('nama', $value)->where('slug', 'permission')->first()->id) $permission = TRUE;
                }
            }
        }
        return $permission;
    }
}

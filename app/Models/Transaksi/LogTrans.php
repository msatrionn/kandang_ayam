<?php

namespace App\Models\Transaksi;

use App\Models\Jurnal\Angkatan;
use App\Models\Master\Produk;
use App\Models\Master\Setup;
use Illuminate\Database\Eloquent\Model;
use App\Models\Transaksi\Purchase;

class LogTrans extends Model
{
    protected $table    =   'log_trans';

    public function purchase()
    {
        return $this->belongsTo(Purchase::class, 'table_id', 'id');
    }
    public static function no_angkatan($id)
    {
        return LogTrans::join('angkatan', 'angkatan.id', 'log_trans.angkatan_id')
            ->where('produk_id', $id)->get();
    }

    public function method()
    {
        return $this->belongsTo(Setup::class, 'kas', 'id');
    }

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'produk_id', 'id');
    }
    public function nama_kandang()
    {
        return $this->belongsTo(Setup::class, 'kandang_id', 'id');
    }
}

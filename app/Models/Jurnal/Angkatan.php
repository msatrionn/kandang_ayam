<?php

namespace App\Models\Jurnal;

use Illuminate\Database\Eloquent\Model;
use App\Models\Master\Setup;
use App\Models\Jurnal\RiwayatKandang;
use App\Models\Jurnal\Populasi;
use App\Models\Transaksi\LogTrans as TransaksiLogTrans;
use Illuminate\Support\Facades\DB;
use LogTrans;

class Angkatan extends Model
{
    protected $table    =   'angkatan';
    protected $appends  =   ['populasi_akhir', 'kematian'];
    protected $guarded  = [];

    public function kandang()
    {
        return $this->belongsTo(Setup::class, 'kandang_sekarang', 'id');
    }
    public function riwayat()
    {
        return $this->belongsTo(RiwayatKandang::class, 'riwayat_kandang_id', 'id');
    }

    public function getPopulasiAkhirAttribute()
    {
        $data   =   Populasi::select(DB::raw("(SUM(populasi_mati) + SUM(populasi_afkir) + SUM(populasi_panen)) AS akhir"))
            ->whereIn('riwayat_id', RiwayatKandang::select('id')->where('angkatan', $this->id))
            ->first();

        return ($this->populasi_awal - $data->akhir);
    }

    public function getKematianAttribute()
    {
        return Populasi::where('riwayat_id', $this->riwayat_kandang_id)->sum('populasi_mati');
    }
}

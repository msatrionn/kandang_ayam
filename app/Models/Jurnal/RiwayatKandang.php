<?php

namespace App\Models\Jurnal;

use Illuminate\Database\Eloquent\Model;
use App\Models\Master\Setup;
use Illuminate\Support\Facades\DB;

class RiwayatKandang extends Model
{
    protected $table    =   'riwayatkandang';
    protected $appends  =   ['populasi_akhir', 'kematian'];

    protected $guarded = [];

    public function farm()
    {
        return $this->belongsTo(Setup::class, 'kandang', 'id')->withTrashed();
    }
    public function produk()
    {
        return $this->belongsTo(Setup::class, 'strain_id', 'id');
    }

    public function getPopulasiAkhirAttribute()
    {
        $data   =   Populasi::select(DB::raw("(SUM(populasi_mati) + SUM(populasi_afkir) + SUM(populasi_panen)) AS akhir"))
            ->where('riwayat_id', $this->id)
            ->first();

        return ($this->populasi - $data->akhir);
    }

    public function getKematianAttribute()
    {
        return Populasi::where('riwayat_id', $this->id)->sum('populasi_mati');
    }
}

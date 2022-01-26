<?php

namespace App\Models\Jurnal;

use Illuminate\Database\Eloquent\Model;

class Populasi extends Model
{
    protected $table = 'populasi';
    protected $guarded = [];
    // protected $fillable = ['hari', 'tanggal_masuk', 'populasi_mati', 'populasi_afkir', 'populasi_panen'];

    public static function view_record($riwayat, $hari)
    {
        return Populasi::where('riwayat_id', $riwayat)->where('hari', $hari)->first();
    }

    public static function sub_total($riwayat, $minggu, $jenis)
    {
        $hitung =   0;
        for ($x = ($minggu - 6); $x <= $minggu; $x++) {
            $data   =   Populasi::select('populasi_mati', 'populasi_afkir', 'populasi_panen')
                ->where('riwayat_id', $riwayat)
                ->where('hari', $x)
                ->first();

            if ($jenis == 'mati') {
                $hitung +=  $data->populasi_mati ?? 0;
            }
            if ($jenis == 'afkir') {
                $hitung +=  $data->populasi_afkir ?? 0;
            }
            if ($jenis == 'panen') {
                $hitung +=  $data->populasi_panen ?? 0;
            }
        }

        return $hitung;
    }
}

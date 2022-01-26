<?php

namespace App\Models\Jurnal;

use Illuminate\Database\Eloquent\Model;

class Timbang extends Model
{
    protected $table = 'timbangayam';
    protected $guarded = [];

    // protected $fillable = ['riwayat_id', 'hari', 'tanggal', 'data_timbang', 'jumlah', 'berat', 'ratarata'];

    public static function hasil_timbang($riwayat, $hari)
    {
        return  Timbang::select('ratarata')
            ->where('riwayat_id', $riwayat)
            ->where('hari', $hari)
            ->first()
            ->ratarata ?? 0;
    }

    public static function timbang_mingguan($riwayat, $minggu)
    {
        $hitung =   0;
        for ($x = ($minggu - 6); $x <= $minggu; $x++) {
            $hitung +=  Timbang::select('ratarata')
                ->where('riwayat_id', $riwayat)
                ->where('hari', $x)
                ->first()
                ->ratarata ?? 0;
        }

        return $hitung;
    }

    public static function unifomity($riwayat, $minggu)
    {
        $hitung =   round(Timbang::timbang_mingguan($riwayat, $minggu), 1);

        $result =   0;
        $ekor   =   0;
        for ($x = ($minggu - 6); $x <= $minggu; $x++) {
            $data   =   Timbang::select('data_timbang', 'jumlah')
                ->where('riwayat_id', $riwayat)
                ->where('hari', $x)
                ->first();

            if ($data) {
                $ekor   +=  $data->jumlah;
                $row    =   json_decode($data->data_timbang);
                $count  =   count($row);

                for ($i = 0; $i < $count; $i++) {
                    if ((($hitung - 0.1) <= $row[$i]) and (($hitung + 0.2)) >= $row[$i]) {
                        $result +=  1;
                    }
                }
            }
        }

        if (($ekor == 0) and ($result == 0)) {
            return 0;
        } elseif (($ekor == 0) or ($result == 0)) {
            return 0;
        } else {
            return round((($result / $ekor) * 100), 2) . '%';
        }
    }
}
//

//

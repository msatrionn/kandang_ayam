<?php

namespace App\Models\Jurnal;

use Illuminate\Database\Eloquent\Model;
use App\Models\Master\Stok;
use App\Models\Auth\User;
use App\Models\Master\Produk;

class KartuStok extends Model
{
    protected $table = 'kartustok';
    protected $guarded = [];
    // protected $fillable = ['recording_id', 'keterangan', 'tanggal_kartu', 'hari', 'tanggal_masuk', 'jenis', 'masuk', 'keluar', 'penerima', 'tipekartu'];

    public function pakan()
    {
        return $this->belongsTo(Produk::class, 'jenis', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'penerima', 'id');
    }

    public static function view_pakan($riwayat, $hari, $field)
    {
        if ($field == 'pakan') {
            $data   =   KartuStok::select('jenis')
                ->where('tipekartu', 'pakan')
                ->where('hari', $hari)
                ->where('recording_id', $riwayat)
                ->groupBy('jenis')
                ->get();

            $row    =   '';
            foreach ($data as $item) {
                if (!empty($item->pakan->nama)) {
                    $row    .=  $item->pakan->nama . ', ';
                }
            }

            return $row;
        } else {
            return KartuStok::where('tipekartu', 'pakan')->where('hari', $hari)->where('recording_id', $riwayat)->sum($field);
        }
    }
    public static function view_pakanid($riwayat, $hari, $field)
    {
        if ($field == 'pakan') {
            $data   =   KartuStok::select('jenis')
                ->where('tipekartu', 'pakan')
                ->where('hari', $hari)
                ->where('recording_id', $riwayat)
                ->groupBy('jenis')
                ->get();

            $row    =   '';
            foreach ($data as $item) {
                if (!empty($item->pakan->id)) {
                    $row    .=  $item->pakan->id . ', ';
                }
            }

            return $row;
        } else {
            return KartuStok::where('tipekartu', 'pakan')->where('hari', $hari)->where('recording_id', $riwayat)->sum($field);
        }
    }

    public static function view_ovk($riwayat, $hari)
    {
        $data   =   KartuStok::select('jenis')
            ->where('tipekartu', 'ovk')
            ->where('hari', $hari)
            ->where('recording_id', $riwayat)
            ->groupBy('jenis')
            ->get();

        $row    =   '';
        foreach ($data as $item) {
            if (!empty($item->pakan->nama)) {
                $row    .=  $item->pakan->nama . ', ';
            }
        }

        return $row;
    }
    public static function view_ovkid($riwayat, $hari)
    {
        $data   =   KartuStok::select('jenis')
            ->where('tipekartu', 'ovk')
            ->where('hari', $hari)
            ->where('recording_id', $riwayat)
            ->groupBy('jenis')
            ->get();

        $row    =   '';
        foreach ($data as $item) {
            if (!empty($item->pakan->id)) {
                $row    .=  $item->pakan->id . ', ';
            }
        }

        return $row;
    }
}

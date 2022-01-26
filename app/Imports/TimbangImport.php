<?php

namespace App\Imports;

use App\Models\Jurnal\RiwayatKandang;
use App\Models\Jurnal\Timbang;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class TimbangImport implements ToModel, WithHeadingRow
{
    /**
     * @param Collection $collection
     */
    public function setAngkatan($angkatan)
    {
        $this->angkatan = $angkatan;
    }
    public function setKandang($kandang)
    {
        $this->kandang = $kandang;
    }
    public function setHari($hari)
    {
        $this->hari = $hari;
    }
    public function model(array $row)
    {
        // $tot = [];
        // $cek[] = array_sum($row);

        return (json_encode($row));


        for ($i = 0; $i < 11; $i++) {
            // dump($row[0]);
            if ($row > 0) {
                $rows = 1;
                // $data                   =   RiwayatKandang::where('kandang', $this->kandang)->first();

                // $timbang                =   Timbang::where('riwayat_id', $data->id)
                //     ->where('hari', $this->hari)
                //     ->first() ?? new Timbang;

                // $timbang->riwayat_id    =   $data->id;
                // $timbang->hari          =   $this->hari;
                // $timbang->tanggal       =   Carbon::parse($data->tanggal)->addDays(($this->hari - 1));

                // $timbang->data_timbang  =   json_encode($row);
                // $timbang->jumlah        =   count($row) ?? 0;
                // $timbang->berat         =   ($rows += count($row)) ?? 0;
                // $timbang->ratarata      =   ($rows += count($row)) > 0 ? round(($rows += count($row)) / count($row), 2) : NULL;
                // $timbang->save();
            }
        }
    }
    public function headingRow(): int
    {
        return 1;
    }
}

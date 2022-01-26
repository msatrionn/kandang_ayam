<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Strain extends Model
{
    use SoftDeletes;
    protected $table = 'strain';

    public static function strain_angka($type, $strain, $hari)
    {
        $data   =   Strain::where('category', $type)
                    ->where('strain_id', $strain)
                    ->where('dari', '<=', $hari)
                    ->where('sampai', '>=', $hari)
                    ->first() ;

        return $data ? $data->angka : 0 ;
    }

    public static function data_strain($type, $id)
    {
        $row    =   '' ;

        $data   =   Strain::where('category', $type)
                    ->where('strain_id', $id)
                    ->get();

        if (COUNT($data)) {
            $row    .=   "<div class='border rounded mb-2 p-2'>" ;
            $row    .=  "<label>" ;
            if ($type == 'gram' ) {
                $row.=  'Konsumsi Pakan Standar Gram/Hari';
            }
            if ($type == 'global' ) {
                $row.=  'Konsumsi Pakan Standar Global' ;
            }
            if ($type == 'bb' ) {
                $row.=    'Berat Badan Standar' ;
            }
            $row    .=  "</label>" ;
        $row.=  "<div class='row'>";
            $row.=  "<div class='col font-weight-bold text-center pr-1'>Minggu</div>";
            $row.=  "<div class='col font-weight-bold text-center px-1'>Mulai</div>";
            $row.=  "<div class='col font-weight-bold text-center px-1'>Sampai</div>";
            $row.=  "<div class='col font-weight-bold text-center pl-1'>Angka</div>";
            $row.=  "<div class='col-1 font-weight-bold pl-1'></div>";
            $row.=  "<div class='col-1 font-weight-bold pl-1'></div>";
            $row.=  "</div>";
            foreach ($data as $item) {
                $row.=  "<div class='border-bottom py-1 px-2'>";
                $row.=  "<div class='row'>";
                $row.=  "<div class='col pr-1'>";
                $row.=  "<input type='number' class='form-control form-control-sm text-center p-1' id='minggu". $item->id ."' value='" . $item->minggu . "'>" ;
                $row.=  "</div>";
                $row.=  "<div class='col px-1'>";
                $row.=  "<input type='number' class='form-control form-control-sm text-center p-1' id='dari". $item->id ."' value='" . $item->dari . "'>" ;
                $row.=  "</div>";
                $row.=  "<div class='col px-1'>";
                $row.=  "<input type='number' class='form-control form-control-sm text-center p-1' id='sampai". $item->id ."' value='" . $item->sampai . "'>" ;
                $row.=  "</div>";
                $row.=  "<div class='col px-1'>";
                $row.=  "<input type='number' class='form-control form-control-sm text-center p-1' id='angka". $item->id ."' value='" . $item->angka . "'>" ;
                $row.=  "</div>";
                $row.=  "<div class='col-1 text-center pt-2 px-1'>";
                $row.=  "<i data-strain='" . $id . "' data-id='" . $item->id . "' class='ubah_standar cursor fa fa-edit text-primary'></i>" ;
                $row.=  "</div>";
                $row.=  "<div class='col-1 text-center pt-2 pl-1'>";
                $row.=  "<i data-strain='" . $id . "' data-id='" . $item->id . "' class='hapus_standar cursor fa fa-trash text-danger'></i>" ;
                $row.=  "</div>";
                $row.=  "</div>";
                $row.=  "</div>";
            }
            $row    .=  "</div>" ;
        }


        return $row ;
    }
}

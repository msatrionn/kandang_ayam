<?php

namespace App\Rules\Jurnal;

use App\Models\Master\Setup;
use Illuminate\Contracts\Validation\Rule;

class InputModal implements Rule
{
    private $id;

    public function __construct($id)
    {
        $this->id   =   $id;
    }

    public function passes($attribute, $value)
    {
        $kas    =   $this->id[0];
        $jenis  =   $this->id[1];

        if ($jenis == 'in') {
            return TRUE ;
        } else {
            return (Setup::hitung_kas($kas) >= $value) ? TRUE : FALSE ;
        }

    }

    public function message()
    {
        return 'Saldo tidak cukup';
    }
}

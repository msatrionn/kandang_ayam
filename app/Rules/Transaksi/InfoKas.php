<?php

namespace App\Rules\Transaksi;

use App\Models\Master\Setup;
use Illuminate\Contracts\Validation\Rule;

class InfoKas implements Rule
{
    private $id;

    public function __construct($id)
    {
        $this->id   =   $id;
    }

    public function passes($attribute, $value)
    {
        return (Setup::hitung_kas($this->id) >= $value) ? TRUE : FALSE ;
    }

    public function message()
    {
        return 'Nominal kas tidak cukup';
    }
}

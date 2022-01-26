<?php

namespace App\Rules\Transaksi;

use App\Models\Master\Setup;
use Illuminate\Contracts\Validation\Rule;

class BayarAset implements Rule
{
    private $id;

    public function __construct($id)
    {
        $this->id   =   $id;
    }

    public function passes($attribute, $value)
    {
        return (Setup::hitung_kas($this->id[0]) >= ($value * $this->id[1] + ($this->id[2] == NULL ? (($value * $this->id[1]) * (10/100)) : 0))) ? TRUE : FALSE ;
    }

    public function message()
    {
        return 'Nominal kas tidak cukup';
    }
}

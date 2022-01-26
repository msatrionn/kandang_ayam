<?php

namespace App\Rules\Transaksi;

use App\Models\Master\Setup;
use App\Models\Transaksi\Purchase;
use Illuminate\Contracts\Validation\Rule;

class BayarPurchase implements Rule
{
    private $id;

    public function __construct($id)
    {
        $this->id   =   $id;
    }

    public function passes($attribute, $value)
    {
        $data   =   Purchase::find($this->id);
        if ($data) {
            $hitung =   ($data->total_harga + ($data->tax ? 0 : $data->total_harga * (10 / 100)) - $data->down_payment);
            return (Setup::hitung_kas($value) >= $hitung) ? TRUE : FALSE ;
        } else {
            return FALSE ;
        }

    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Nominal kas tidak cukup';
    }
}

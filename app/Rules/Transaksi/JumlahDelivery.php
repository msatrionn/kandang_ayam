<?php

namespace App\Rules\Transaksi;

use App\Models\Transaksi\Delivery;
use App\Models\Transaksi\Purchase;
use Illuminate\Contracts\Validation\Rule;

class JumlahDelivery implements Rule
{
    private $id ;

    public function __construct($id)
    {
        $this->id   =   $id;
    }

    public function passes($attribute, $value)
    {
        $data   =   Purchase::find($this->id);

        return ($value <= ($data->qty - $data->jumlah_terkirim)) ? TRUE : FALSE ;
    }

    public function message()
    {
        return 'Jumlah pengiriman melebihi';
    }
}

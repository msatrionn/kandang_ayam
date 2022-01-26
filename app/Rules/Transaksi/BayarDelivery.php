<?php

namespace App\Rules\Transaksi;

use App\Models\Master\Setup;
use Illuminate\Contracts\Validation\Rule;

class BayarDelivery implements Rule
{
    private $total;

    public function __construct($total)
    {
        $this->total    =   $total;
    }

    public function passes($attribute, $value)
    {
        if ($value) {
            $payment    =   Setup::where('slug', 'payment')
                            ->where('id', $value)
                            ->count();

            if ($payment) {
                return (Setup::hitung_kas($value) >= $this->total) ? TRUE : FALSE;
            } else {
                return FALSE ;
            }

        } else {
            return TRUE ;
        }

    }

    public function message()
    {
        return 'Nominal kas tidak cukup';
    }
}

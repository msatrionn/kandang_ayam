<?php

namespace App\Models\Master;

use App\Models\Jurnal\Aset;
use App\Models\Jurnal\Populasi;
use App\Models\Jurnal\RiwayatKandang;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Master\Produk;
use App\Models\Transaksi\HeaderTrans;
use App\Models\Transaksi\LogTrans;
use App\Models\Transaksi\PosisiAyam;
use App\Models\Transaksi\Purchase;

class Setup extends Model
{
    use SoftDeletes;
    protected $table    =   'setup';
    protected $appends  =   ['pengeluaran'];

    public function getPengeluaranAttribute()
    {
        $data   =   LogTrans::where('status', 1)
            ->whereIn('produk_id', Produk::select('id')->where('tipe', $this->id)->where('jenis', 'purchase'))
            ->sum('nominal');

        return $data;
    }

    public static function pengeluaran($jenis)
    {
        $data   =   LogTrans::where('status', 1)
            ->where(function ($query) use ($jenis) {
                if ($jenis == 'pembelian_tetap') {
                    $query->where('jenis', 'pembelian_lain')->whereIn('produk_id', Produk::select('id')->where('tipe', 1));
                } else
                        if ($jenis == 'pembelian_lain') {
                    $query->where('jenis', 'pembelian_lain')->whereNotIn('produk_id', Produk::select('id')->where('tipe', 1));
                } else {
                    $query->where('jenis', $jenis);
                }
            })
            ->sum('nominal');

        return $data;
    }

    public function productsatuan()
    {
        return $this->hasMany(Produk::class, 'satuan', 'id');
    }

    public static function info_kas($tipe)
    {
        $data   =   Setup::where('slug', 'payment')
            ->where('status', $tipe)
            ->get();

        $row    =   '';
        foreach ($data as $item) {
            $masuk      =   HeaderTrans::whereIn('jenis', ['penjualan_lain', 'penjualan_ayam', 'setor_modal', 'mutasi_masuk'])
                ->where('status', 1)
                ->where('payment_method', $item->id)
                ->whereYear('tanggal', date('Y'))
                ->sum('total_trans');

            $keluar     =   HeaderTrans::whereIn('jenis', ['beban', 'pengeluaran', 'pengeluaran_lain', 'tarik_modal', 'beli_aset', 'mutasi_keluar'])
                ->where('status', 1)
                ->where('payment_method', $item->id)
                ->whereYear('tanggal', date('Y'))
                ->sum('total_trans');

            $pengurang  =   LogTrans::whereIn('jenis', ['dp', 'pelunasan', 'beban_angkut', 'biaya_kirim', 'biaya_lain_lain', 'gaji', 'pembelian_lain', 'cashbon'])
                ->where('status', 1)
                ->where('kas', $item->id)
                ->whereYear('tanggal', date('Y'))
                ->sum('nominal');

            $row    .=  "<div class='border-bottom p-1'>";
            $row    .=  "<div class='row'>";
            $row    .=  "<div class='col pr-1'>";
            $row    .=  $item->nama;
            $row    .=  "</div>";
            $row    .=  "<div class='col-auto pl-1'>";
            $row    .=  number_format($masuk - ($keluar + $pengurang));
            $row    .=  "</div>";
            $row    .=  "</div>";
            $row    .=  "</div>";
        }

        return $row;
    }

    public static function hitung_kas($id)
    {
        $masuk      =   HeaderTrans::whereIn('jenis', ['penjualan_lain', 'penjualan_ayam', 'setor_modal', 'mutasi_masuk'])
            ->where('payment_method', $id)
            ->where('status', 1)
            ->sum('total_trans');

        $keluar     =   HeaderTrans::whereIn('jenis', ['beban', 'pengeluaran', 'pengeluaran_lain', 'tarik_modal', 'beli_aset', 'mutasi_keluar'])
            ->where('payment_method', $id)
            ->where('status', 1)
            ->sum('total_trans');

        $pengurang  =   LogTrans::whereIn('jenis', ['dp', 'pelunasan', 'beban_angkut', 'biaya_kirim', 'biaya_lain_lain', 'pembelian_lain', 'cashbon'])
            ->where('status', 1)
            ->where('kas', $id)
            ->sum('nominal');

        return $masuk - ($keluar + $pengurang);
    }

    public function listpay()
    {
        return $this->hasMany(HeaderTrans::class, 'payment_method', 'id');
    }

    public function producttipe()
    {
        return $this->hasMany(Produk::class, 'tipe', 'id');
    }

    public function setupaset()
    {
        return $this->hasMany(Aset::class, 'kategori', 'id');
    }

    public static function kekata($x)
    {
        $x = abs($x);
        $angka  =   ["", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas"];
        $temp   =   "";
        if ($x < 12) {
            $temp   =   " " . $angka[$x];
        } else if ($x < 20) {
            $temp   =   Setup::kekata($x - 10) . " belas";
        } else if ($x < 100) {
            $temp   =   Setup::kekata($x / 10) . " puluh" . Setup::kekata($x % 10);
        } else if ($x < 200) {
            $temp   =   " seratus" . Setup::kekata($x - 100);
        } else if ($x < 1000) {
            $temp   =   Setup::kekata($x / 100) . " ratus" . Setup::kekata($x % 100);
        } else if ($x < 2000) {
            $temp   =   " seribu" . Setup::kekata($x - 1000);
        } else if ($x < 1000000) {
            $temp   =   Setup::kekata($x / 1000) . " ribu" . Setup::kekata($x % 1000);
        } else if ($x < 1000000000) {
            $temp   =   Setup::kekata($x / 1000000) . " juta" . Setup::kekata($x % 1000000);
        } else if ($x < 1000000000000) {
            $temp   =   Setup::kekata($x / 1000000000) . " milyar" . Setup::kekata(fmod($x, 1000000000));
        } else if ($x < 1000000000000000) {
            $temp   =   Setup::kekata($x / 1000000000000) . " trilyun" . Setup::kekata(fmod($x, 1000000000000));
        }
        return $temp;
    }

    public static function tkoma($x)
    {
        $str = stristr($x, ",");
        $ex = explode(',', $x);

        if (($ex[1] / 10) >= 1) {
            $a = abs($ex[1]);
        }
        $string = array("nol", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas");
        $temp = "";

        $a2 = $ex[1] / 10;
        $pjg = strlen($str);
        $i = 1;


        if ($a >= 1 && $a < 12) {
            $temp .= " " . $string[$a];
        } else if ($a > 12 && $a < 20) {
            $temp .= konversi($a - 10) . " belas";
        } else if ($a > 20 && $a < 100) {
            $temp .= konversi($a / 10) . " puluh" . konversi($a % 10);
        } else {
            if ($a2 < 1) {

                while ($i < $pjg) {
                    $char = substr($str, $i, 1);
                    $i++;
                    $temp .= " " . $string[$char];
                }
            }
        }
        return $temp;
    }


    public static function terbilang($x, $style = 4)
    {
        $before     =   trim(Setup::kekata($x));
        $after      =   trim(Setup::comma($x));
        $hasil  = ($x < 0) ? "minus " . $before . ($after == '' ? '' : $after . ' sen') : $before . ($after == '' ? '' : $after . ' sen');
        switch ($style) {
            case 1:
                $hasil = strtoupper($hasil);
                break;
            case 2:
                $hasil = strtolower($hasil);
                break;
            case 3:
                $hasil = ucwords($hasil);
                break;
            default:
                $hasil = ucfirst($hasil);
                break;
        }
        return $hasil;
    }

    public static function comma($number)
    {
        $after_comma = stristr($number, '.');
        $arr_number = ["nol", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan"];
        $results = "";
        $length = strlen($after_comma);
        $i = 1;
        while ($i < $length) {
            $get = substr($after_comma, $i, 1);
            $results .= " " . $arr_number[$get];
            $i++;
        }
        return $results;
    }
}

<?php

namespace App\Models\Transaksi;

use App\Models\Auth\User;
use App\Models\Master\Produk;
use App\Models\Master\Setup;
use App\Models\Master\Supplier;
use App\Models\Transaksi\LogTrans;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Purchase extends Model
{
    use SoftDeletes;
    protected $table    =   'purchase';
    protected $appends  =   ['nomor_purchasing', 'jumlah_terkirim'];

    public function product()
    {
        return $this->belongsTo(Produk::class, 'produk_id', 'id')->withTrashed();
    }

    public function type()
    {
        return $this->belongsTo(Setup::class, 'tipe', 'id')->withTrashed();
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id', 'id')->withTrashed();
    }

    public function antar()
    {
        return $this->hasMany(Delivery::class, 'purchase_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function pembayaran()
    {
        return $this->belongsTo(Setup::class, 'kas', 'id');
    }

    public function kandang()
    {
        return $this->belongsTo(Setup::class, 'kandang_id', 'id');
    }

    public function logtrans()
    {
        return $this->hasMany(LogTrans::class, 'table_id', 'id')->where('table', 'purchase')->where('jenis', 'dp')->where('status', 1);
    }

    public static function getnomor_purchase()
    {
        $data   =   Purchase::select('nomor')
                    ->whereYear('tanggal', date('Y'))
                    ->orderBy('id', 'DESC')
                    ->limit(1)
                    ->first();

        return $data ? ($data->nomor + 1) : 1 ;
    }

    public function getNomorPurchasingAttribute()
    {
        return 'PO-' . str_pad((string)$this->nomor, 4, "0", STR_PAD_LEFT);
    }

    public function getJumlahTerkirimAttribute()
    {
        $delived =   Delivery::where('purchase_id', $this->id)->get();

        $total  =   0;
        foreach ($delived as $row) {
            $total  +=  $row->qty;
        }

        return $total ;
    }
}

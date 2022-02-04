<?php

namespace App\Models\Master;

use App\Models\Master\Supplier;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Master\Setup;
use App\Models\Master\Stok;

class Produk extends Model
{
    use SoftDeletes;
    protected $table    =   'produk';
    protected $appends  =   ['jumlah_stock'];

    public function supply()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id', 'id')->withTrashed();
    }
    public static function getProduk($id)
    {
        return Produk::find($id)->nama;
    }

    public function tipeset()
    {
        return $this->belongsTo(Setup::class, 'tipe', 'id')->withTrashed();
    }

    public function tipesatuan()
    {
        return $this->belongsTo(Setup::class, 'satuan', 'id')->withTrashed();
    }
    public static function select_produk()
    {
        $data       =   Produk::orderBy('nama', 'ASC');

        $row    =   '';
        foreach ($data->get() as $list) {
            if ($list->supplier_id == NULL) {
                $row    .=  "<option value='" . $list->id . "'>" . $list->nama . "</option>";
            }
        }

        $data   =   $data->whereNotIn('tipe', Stok::select('tipe'))
            ->whereIn('id', Stok::select('produk_id')->where('stock_opname', '>', 0))
            ->pluck('nama', 'id');

        foreach ($data as $id => $list) {
            $row    .=  "<option value='" . $id . "'>" . $list . "</option>";
        }

        return $row;
    }

    public function getJumlahStockAttribute()
    {
        return  Stok::where('produk_id', $this->id)->sum('stock_opname');
    }

    public static function produk_purchase()
    {
        $data   =   Produk::orderBy('nama', 'ASC')
            ->where('jenis', 'purchase')
            ->get();

        $list   =   '';
        foreach ($data as $row) {
            $list    .=  "<option value='" . $row->id . "'>[" . $row->tipeset->nama . "] " . $row->nama . "</option>";
        }

        return $list;
    }
}

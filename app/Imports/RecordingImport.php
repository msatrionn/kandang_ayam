<?php

namespace App\Imports;

use App\Models\Jurnal\Catatan;
use App\Models\Jurnal\KartuStok;
use App\Models\Jurnal\Populasi;
use App\Models\Jurnal\RiwayatKandang;
use App\Models\Jurnal\Timbang;
use App\Models\Jurnal\Vaksinasi;
use App\Models\Master\Produk;
use App\Models\Master\Setup;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
// use Maatwebsite\Excel\Concerns\WithMappedCells;


class RecordingImport implements ToModel, WithHeadingRow
// , WithMappedCells
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    // public function mapping(): array
    // {
    //     return [
    //         'tanggal_masuk'  => 'A8',
    //         'hari' => 'B8',
    //         'masuk' => 'H8',
    //         'keluar' => 'I8',
    //         'populasi_mati' => 'D8',
    //         'populasi_mati' => 'D8',
    //         'populasi_afkir' => 'E8',
    //         'populasi_panen' => 'F8',
    //         'jenis' => 'O8',

    //     ];
    // }
    private $tanggal;

    public function settanggal($tanggal)
    {
        $this->tanggal = $tanggal;
    }

    public function model(array $row)
    {
        // DB::beginTransaction();

        if (!empty($row['jenis'])) {
            $jenis = Produk::where('nama', 'like' . '%' . $row['jenis'] . '%')->first();
        } else {
            $jenis = 0;
        }
        if (!empty($row['datang'])) {
            $masuk = $row['datang'];
        } else {
            $masuk = 0;
        }
        if (!empty($row['kghari'])) {
            $keluar = $row['kghari'];
        } else {
            $keluar = 0;
        }
        if (!empty($row['mati'])) {
            $populasi_mati = $row['mati'];
        } else {
            $populasi_mati = 0;
        }
        if (!empty($row['afkir'])) {
            $populasi_afkir = $row['afkir'];
        } else {
            $populasi_afkir = 0;
        }
        if (!empty($row['panen'])) {
            $populasi_panen = $row['panen'];
        } else {
            $populasi_panen = 0;
        }
        if (!empty($row['hari'])) {
            $hari = $row['hari'];
        } else {
            $hari = 0;
        }
        if (!empty($row['keterangan'])) {
            $keterangan = $row['keterangan'];
        } else {
            $keterangan = 0;
        }

        $kandang = Setup::where('nama', $row['kandang'])->first();
        if (!empty($kandang)) {
            $kandang = $kandang->id;
        } else {
            $kandang = 0;
        }
        $jenis_pakan = Produk::where('nama', $row['jenis_pakan'])->first();
        if (!empty($jenis_pakan)) {
            $jenis_pakan = $jenis_pakan->id;
        } else {
            $jenis_pakan = 0;
        }
        $ovk = Produk::where('nama', $row['ovk'])->first();
        if (!empty($ovk)) {
            $ovk = $ovk->id;
        } else {
            $ovk = 0;
        }
        $strain = Setup::where('nama', $row['strain'])->first();
        if (!empty($strain)) {
            $strain = $strain->id;
        } else {
            $strain = 0;
        }
        $data       =   RiwayatKandang::where('kandang', $kandang)->first();

        $dates = (array)\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['tgl']);

        foreach ($dates as $key => $value) {
            $tanggal = date('Y-m-d', strtotime($value));
        }

        if (KartuStok::where('hari', $hari)->where('tipekartu', 'pakan')->where('edited', 'sudah diedit')->first()) {
            $kartu = KartuStok::where('hari', $hari)->where('tipekartu', 'pakan')->first();
            $kartu_masuk = KartuStok::where('tipekartu', 'pakan')->where('edited', 'sudah diedit')->sum('masuk');
            // dd($kartu_masuk)

            $kartu->update([
                'tanggal_masuk'     => $dates['date'],
                'hari'    => $hari,
                'masuk'    => $masuk,
                'keluar'    => $keluar,
                'jenis'    => $jenis_pakan,
                'tipekartu'    => "pakan",
                'recording_id'    => $row['periode'],
                'tanggal_kartu'    =>  $dates['date'],
                'keterangan'    =>  $keterangan,
                'edited'    =>  'belum edit',

            ]);
            $stok = DB::table('stock_kandang')->where('kandang_id', $kandang)
                ->where('tipe', '2')
                ->where('produk_id', $jenis_pakan)
                ->orderBy('sisa', 'desc')
                ->take(1)->first()->sisa ?? 0;
            DB::table('stock_kandang')->where('kandang_id', $kandang)
                ->where('tipe', '2')
                ->where('produk_id', $jenis_pakan)
                ->orderBy('sisa', 'desc')
                // ->take(1)->update(['sisa' => ($stokan - $request->jumlah)]);
                ->take(1)->update(['sisa' => ($stok + $kartu_masuk)]);
        } elseif (KartuStok::where('hari', $hari)->where('tipekartu', 'pakan')->first()) {
            $kartu = KartuStok::where('hari', $hari)->where('tipekartu', 'pakan')->first();

            $kartu->update([
                'tanggal_masuk'     => $dates['date'],
                'hari'    => $hari,
                'masuk'    => $masuk,
                'keluar'    => $keluar,
                'jenis'    => $jenis_pakan,
                'tipekartu'    => "pakan",
                'recording_id'    => $row['periode'],
                'tanggal_kartu'    =>  $dates['date'],
                'keterangan'    =>  $keterangan,
                'edited'    =>  'belum edit',

            ]);
        } else {
            $kartu = new KartuStok([
                'tanggal_masuk'     => $dates['date'],
                'hari'    => $hari,
                'masuk'    => $masuk,
                'keluar'    => $keluar,
                'jenis'    => $jenis_pakan,
                'tipekartu'    => "pakan",
                'recording_id'    => $row['periode'],
                'tanggal_kartu'    =>  $dates['date'],
                'keterangan'    =>  $keterangan,
                'edited'    =>  'belum edit',
            ]);
        }
        if (Populasi::where('hari', $hari)->first()) {
            $populasi = Populasi::where('hari', $hari)->first();
            $populasi->update([
                'riwayat_id' => $row['periode'],
                'tanggal_masuk'     =>  $dates['date'],
                'tanggal_input'    =>  $dates['date'],
                'hari'    => $hari,
                'kandang'    => $kandang,
                'populasi_mati'     => $populasi_mati,
                'populasi_afkir'    => $populasi_afkir,
                'populasi_panen'    => $populasi_panen,
            ]);
        } else {
            $populasi = new Populasi([
                'riwayat_id' => $row['periode'],
                'tanggal_masuk'     =>  $dates['date'],
                'tanggal_input'    =>  $dates['date'],
                'hari'    => $hari,
                'kandang'    => $kandang,
                'populasi_mati'     => $populasi_mati,
                'populasi_afkir'    => $populasi_afkir,
                'populasi_panen'    => $populasi_panen,
            ]);
        }
        if (!empty(Catatan::where('hari', $hari)->first())) {
            $catatan = Catatan::where('hari', $hari)->first();
            $catatan->update([
                'riwayat_id' => $row['periode'],
                'hari'    => $hari,
                'user_id'    => null,
                // 'user_id'    => auth()->user()->id,
                'data_catatan'     => $keterangan,
            ]);
        } else {
            $catatan = new Catatan([
                'riwayat_id' => $row['periode'],
                'hari'    => $hari,
                'user_id'    => null,
                // 'user_id'    => auth()->user()->id,
                'data_catatan'     => $keterangan,
            ]);
        }


        if (KartuStok::where('hari', $hari)->where('tipekartu', 'ovk')->where('edited', 'sudah diedit')->first()) {
            $ovkvak = KartuStok::where('hari', $hari)->where('tipekartu', 'ovk')->first();
            $ovk_masuk = KartuStok::where('tipekartu', 'ovk')->where('edited', 'sudah diedit')->sum('masuk');

            $ovkvak->update([
                'tanggal_masuk'     => $dates['date'],
                'hari'    => $hari,
                // 'masuk'    => 0,
                // 'keluar'    => 0,
                'jenis'    => $ovk,
                'tipekartu'    => "ovk",
                'recording_id'    => $row['periode'],
                'tanggal_kartu'    =>  $dates['date'],
                'keterangan'    =>  $keterangan,
                'edited'    =>  'belum edit',

            ]);
            $stok = DB::table('stock_kandang')->where('kandang_id', $kandang)
                ->where('tipe', '1')
                ->where('produk_id', $ovk)
                ->orderBy('sisa', 'desc')
                ->take(1)->first()->sisa ?? 0;
            DB::table('stock_kandang')->where('kandang_id', $kandang)
                ->where('tipe', '1')
                ->where('produk_id', $ovk)
                ->orderBy('sisa', 'desc')
                // ->take(1)->update(['sisa' => ($stokan - $request->jumlah)]);
                ->take(1)->update(['sisa' => ($stok + $ovk_masuk)]);
        } elseif (KartuStok::where('hari', $hari)->where('tipekartu', 'ovk')->first()) {
            $ovkvak = KartuStok::where('hari', $hari)->where('tipekartu', 'ovk')->first();
            $ovkvak->update([
                'tanggal_masuk'     => $dates['date'],
                'hari'    => $hari,
                'masuk'    => 0,
                'keluar'    => 0,
                'jenis'    => $ovk,
                'tipekartu'    => "ovk",
                'recording_id'    => $row['periode'],
                'tanggal_kartu'    =>  $dates['date'],
                'keterangan'    =>  $keterangan,
                'edited'    =>  'belum edit',

            ]);
        } else {
            $ovkvak = new KartuStok([
                'tanggal_masuk'     => $dates['date'],
                'hari'    => $hari,
                'masuk'    => 0,
                'keluar'    => 0,
                'jenis'    => $ovk,
                'tipekartu'    => "ovk",
                'recording_id'    => $row['periode'],
                'tanggal_kartu'    =>  $dates['date'],
                'keterangan'    =>  $keterangan,
                'edited'    =>  'belum edit',

            ]);
        }
        // if (condition) {
        //     $vaksin = new Vaksinasi([
        //         'riwayat_id'    => $row['periode'],
        //         'umur'    => $hari,
        //         'tanggal'     => $dates['date'],
        //         'vaksin'    => $ovk,
        //     ]);
        // }

        // $timbang = new Timbang([
        //     "riwayat_id"    =>   $row['periode'],
        //     "hari"          =>   $hari,
        //     "tanggal"       =>    $dates['date'],
        //     // "data_timbang"  =>   json_encode($request->hitung),
        //     "jumlah"        =>   $row['grekor'] ?? 0,
        //     "berat"         =>   $row['std'] ?? 0,
        //     "ratarata"      =>  $row['grekor'] > 0 ? round($row['grekor'] / $row['grekor'], 2) : NULL,
        // ]);
        // DB::commit();
        // if ($data->tanggal == 'asdsad') {
        $save = [$kartu, $populasi, $catatan, $ovkvak];
        return $save;
    }
    public function headingRow(): int
    {
        return 1;
    }
}

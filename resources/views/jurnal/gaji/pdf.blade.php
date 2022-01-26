@extends('layouts.pdf')

@section('title', 'Slip Gaji')

@section('content')
<table>
    <tbody>
        <tr>
            <td>Tanggal</td>
            <td class="padding: 0 10px">:</td>
            <td>{{ Tanggal::date($data->tanggal) }}</td>
        </tr>
        <tr>
            <td>Nama Karyawan</td>
            <td class="padding: 0 10px">:</td>
            <td>{{ $data->karyawan->name }}</td>
        </tr>
        <tr>
            <td colspan="3">&nbsp;</td>
        </tr>
    </tbody>
</table>

<div style="float: left; width: 50%">
    <table>
        <tbody>
            <tr>
                <td>Gaji Pokok</td>
                <td class="padding: 0 10px">:</td>
                <td>Rp {{ number_format($data->besar_gaji * $data->hari_gaji) }}</td>
            </tr>
            <tr>
                <td>Over Time</td>
                <td class="padding: 0 10px">:</td>
                <td>Rp {{ number_format($data->besar_overtime * $data->perkalian_overtime) }}</td>
            </tr>
            <tr>
                <td>Potongan Gaji</td>
                <td class="padding: 0 10px">:</td>
                <td>Rp {{ number_format($data->potong_gaji) }}</td>
            </tr>
            <tr>
                <td>THR</td>
                <td class="padding: 0 10px">:</td>
                <td>Rp {{ number_format($data->thr) }}</td>
            </tr>
            <tr>
                <td>Bonus</td>
                <td class="padding: 0 10px">:</td>
                <td>Rp {{ number_format($data->bonus) }}</td>
            </tr>
            <tr>
                <td>Keterangan Bonus</td>
                <td class="padding: 0 10px">:</td>
                <td>{{ $data->keterangan }}</td>
            </tr>
            <tr>
                <td>Cicilan Cashbon</td>
                <td class="padding: 0 10px">:</td>
                <td>Rp {{ number_format($data->cashbon) }}</td>
            </tr>
        </tbody>
    </table>
</div>

<div style="float: left; width: 50%">
    <table>
        <tbody>
            <tr>
                <td>Gaji Diperoleh</td>
                <td class="padding: 0 10px">:</td>
                <td>Rp {{ number_format($data->total_didapat) }}</td>
            </tr>
            <tr>
                <td>Terbilang</td>
                <td class="padding: 0 10px">:</td>
                <td>{{ Option::terbilang($data->total_didapat, 1) }} RUPIAH</td>
            </tr>
            <tr>
                <td>Pembayaran Gaji</td>
                <td class="padding: 0 10px">:</td>
                <td>{{ $data->pay->nama }}</td>
            </tr>
        </tbody>
    </table>
</div>
<div style="clear: both"></div>

<div style="padding-top: 50px; float: right">
    <table>
        <tr>
            <th>Diterima,</th>
            <th></th>
            <th>Mengetahui,</th>
        </tr>
        <tr>
            <td><br><br><br><br><br>({{ $data->karyawan->name }})</td>
            <td style="width: 50px">&nbsp;</td>
            <td><br><br><br><br><br>(..................................................)</td>
        </tr>
    </table>
</div>
@endsection

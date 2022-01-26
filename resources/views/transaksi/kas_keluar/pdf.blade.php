@extends('layouts.pdf')

@section('title', 'Bukti Kas Keluar')

@section('content')
<div style="float: left; width: 400px">
    <table>
        <tbody>
            <tr>
                <td style="width: 110px">Rekening</td>
                <td style="width: 5px">:</td>
                <td>{{ $trans->method->nama ?? '' }}</td>
            </tr>
            <tr>
                <td>Dibayarkan kepada</td>
                <td>:</td>
                <td>{{ $trans->purchase->supplier->nama }}</td>
            </tr>
            <tr>
                <td>Jumlah</td>
                <td>:</td>
                <td>Rp {{ number_format($trans->nominal) }}</td>
            </tr>
            <tr>
                <td>Terbilang</td>
                <td>:</td>
                <td>{{ Option::terbilang($trans->nominal, 1) }} RUPIAH</td>
            </tr>
        </tbody>
    </table>
</div>
<div style="float: right; width: 200px;">
    <table>
        <tbody>
            <tr>
                <td>Nomor</td>
                <td>:</td>
                <td>{{ $trans->nomor }}</td>
            </tr>
            <tr>
                <td>Tanggal</td>
                <td>:</td>
                <td>{{ Tanggal::date($trans->tanggal) }}</td>
            </tr>
        </tbody>
    </table>
</div>

<div class="clear"></div>
<div style="padding-top: 10px"><b>Keterangan :</b></div>
Pelunasan purchasing order nomor {{ $trans->purchase->nomor_purchasing }}

<div style="padding-top: 50px; float: right">
    <table>
        <tr>
            <th>Disetujui,</th>
            <th></th>
            <th>Dibayarkan Oleh,</th>
            <th></th>
            <th>Dibukukan Oleh,</th>
        </tr>
        <tr>
            <td><br><br><br><br><br>(..................................................)</td>
            <td style="width: 30px">&nbsp;</td>
            <td><br><br><br><br><br>(..................................................)</td>
            <td style="width: 30px">&nbsp;</td>
            <td><br><br><br><br><br>(..................................................)</td>
        </tr>
    </table>
</div>
@endsection

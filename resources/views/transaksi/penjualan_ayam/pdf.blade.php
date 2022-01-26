@extends('layouts.pdf')

@section('title', 'Invoice')
@section('periode', $data->nomor_transaksi )

@section('content')
<table>
    <tbody>
        <tr>
            <td>Tanggal</td>
            <td>:</td>
            <td>{{ Tanggal::date($data->tanggal) }}</td>
        </tr>
        <tr>
            <td>Nama Konsumen</td>
            <td>:</td>
            <td>{{ $data->nama_konsumen }}</td>
        </tr>
        <tr>
            <td>Jumlah Ayam</td>
            <td>:</td>
            <td>{{ number_format(Header::jml_trans_head($data->id)) }} Ekor</td>
        </tr>
        <tr>
            <td>Total Transaksi</td>
            <td>:</td>
            <td>Rp {{ number_format($data->total_trans) }}</td>
        </tr>
        <tr>
            <td>Terbilang</td>
            <td>:</td>
            <td>:: {{ Option::terbilang($data->total_trans, 1) }} RUPIAH ::</td>
        </tr>
        <tr>
            <td>Metode Pembayaran</td>
            <td>:</td>
            <td>{{ $data->method->nama }}</td>
        </tr>
    </tbody>
</table>

<div style="margin: 25px 0">
    <table width="100%" border="1">
        <thead>
            <tr>
                <th>No</th>
                <th>Angkatan</th>
                <th>Kandang</th>
                <th>Lokasi</th>
                <th>Kode</th>
                <th>Umur</th>
                <th>Jumlah</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data->list_trans as $i => $row)
            @php
                $exp    =   json_decode($row->riwayat->farm->json_data) ;
                $hari   =   date_diff( date_create($data->tanggal), date_create($row->riwayat->tanggal) );
            @endphp
            <tr>
                <td>{{ ++$i }}</td>
                <td>Angkatan {{ $row->riwayat->angkatan }}</td>
                <td>{{ $row->riwayat->farm->nama }}</td>
                <td>Bangunan {{ $exp->bangunan ?? '' }}</td>
                <td>{{ $exp->kode ?? '' }}</td>
                <td>{{ $hari->d + 1 }} Hari</td>
                <td style="text-align: right">{{ $row->qty }} Ekor</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

@if ($data->perubahan_transaksi)
<div style="float: left">
    Perubahan transaksi ke : {{ $data->perubahan_transaksi }}
</div>
@endif

@if ($data->relasi_perubahan)
<div style="float: left">
    Perubahan atas transaksi : {{ $data->relasi_perubahan }}
</div>
@endif

<div style="padding-top: 30px; float: right">
    <table>
        <tr>
            <th>Hormat Kami,</th>
        </tr>
        <tr>
            <td><br><br><br><br><br>(..................................................)</td>
        </tr>
    </table>
</div>
@endsection

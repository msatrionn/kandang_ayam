@extends('layouts.pdf')

@section('title', 'Data Konsumen')

@section('content')
<table>
    <tbody>
        <tr>
            <td style="width: 120px">Nama Konsumen</td>
            <td>:</td>
            <td>{{ $data->nama_konsumen }}</td>
        </tr>
        <tr>
            <td>Nomor Telepon</td>
            <td>:</td>
            <td>{{ $data->telepon }}</td>
        </tr>
        <tr>
            <td>Alamat</td>
            <td>:</td>
            <td>{{ $data->alamat }}</td>
        </tr>
    </tbody>
</table>

<div style="margin-top: 30px">
    <div style="margin-bottom: 10px"><b>DAFTAR TRANSAKSI</b></div>
    <table border="1" width="100%">
        <thead>
            <tr>
                <th>#</th>
                <th>Nomor</th>
                <th>Tanggal</th>
                <th>Keterangan</th>
                <th>Total Transaksi</th>
                <th>Pembayaran</th>
                <th>Operator</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($trans as $i => $row)
            <tr>
                <td>{{ ++$i }}</td>
                <td>{{ $row->nomor_transaksi }}</td>
                <td>{{ Tanggal::date($row->tanggal) }}</td>
                <td>{{ $row->keterangan }}</td>
                <td style="text-align: right">Rp {{ number_format($row->total_trans) }}</td>
                <td>{{ $row->method->nama }}</td>
                <td>{{ $row->user->name }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection

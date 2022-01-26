@extends('layouts.pdf')

@section('title', 'Data Supplier')

@section('content')
<table>
    <tbody>
        <tr>
            <td style="width: 120px">Nama Supplier</td>
            <td>:</td>
            <td>{{ $data->nama }}</td>
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
                <th>Produk</th>
                <th>Jumlah</th>
                <th>Tanggal Purchase</th>
                <th>Sub Harga</th>
                <th>PPN</th>
                <th>Down Payment</th>
                <th>Total Harga</th>
                <th>Termin</th>
                <th>Operator</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($purchase as $i => $row)
            <tr>
                <td>{{ ++$i }}</td>
                <td>{{ $row->nomor_purchasing }}</td>
                <td>{{ $row->product->nama }}</td>
                <td>{{ number_format($row->qty) }} {{ $row->product->tipesatuan->nama }}</td>
                <td>{{ Tanggal::date($row->tanggal) }}</td>
                <td style="text-align: right">Rp {{ number_format($row->total_harga) }}</td>
                <td style="text-align: right">Rp {{ number_format(($row->tax == 1) ? ($row->total_harga * (10/100)) : 0) }}</td>
                <td style="text-align: right">Rp {{ number_format($row->down_payment) }}</td>
                <td style="text-align: right">Rp {{ number_format(($row->total_harga + (($row->tax == 1) ? ($row->total_harga * (10/100)) : 0)) - $row->down_payment) }}</td>
                <td>{{ $row->termin }} Hari</td>
                <td>{{ $row->user->name }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection

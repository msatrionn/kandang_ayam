@extends('layouts.pdf')

@section('title', 'Purchasing Order')

@section('content')
<div style="float:left; width: 250px">
    <div>KEPADA :</div>
    <div style="text-transform: uppercase"><b>{{ $data->supplier->nama }}</b></div>
    {{ $data->supplier->alamat }}
    <div>{{ $data->supplier->telepon }}</div>
</div>

<div style="float:right; width:250px">
    <table>
        <tbody>
            <tr>
                <td>Nomor Purchase</td>
                <td style="width:20px; text-align: center">:</td>
                <td>{{ $data->nomor_purchasing }}</td>
            </tr>
            <tr>
                <td>Tanggal Purchase</td>
                <td style="width:20px; text-align: center">:</td>
                <td>{{ Tanggal::date($data->tanggal) }}</td>
            </tr>
            <tr>
                <td>Tanggal Termin</td>
                <td style="width:20px; text-align: center">:</td>
                <td>{{ Tanggal::date($data->termin_tanggal) }}</td>
            </tr>
            <tr>
                <td>Kandang</td>
                <td style="width:20px; text-align: center">:</td>
                <td>{{ $data->kandang_id ? $data->kandang->nama : '-' }}</td>
            </tr>
        </tbody>
    </table>
</div>

<div class="clear"></div>
<div style="padding-top: 20px">
    <table width="100%" border="1">
        <thead>
            <tr>
                <th>Nama Barang</th>
                <th>Qty</th>
                <th>Harga Satuan</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach (json_decode($data->produk) as $item)
            <tr>
                <td>{{ Produk::find($item->produk)->nama }}</td>
                <td style="text-align: right">{{ number_format($item->jumlah) }}</td>
                <td style="text-align: right">{{ number_format($item->harga) }}</td>
                <td style="text-align: right">{{ number_format($item->harga * $item->jumlah) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

@if ($data->keterangan)
<div style="width: 400px; float: left; padding-top: 25px">
    <div style="font-weight: bold">Keterangan :</div>
    {{ $data->keterangan }}
</div>
@endif

<div style="float: right; border-top:2px solid #000; margin-top: 25px">
<table style="width: 200px;">
    <tbody>
        <tr>
            <td>Sub Total</td>
            <td>:</td>
            <td style="text-align: right">{{ number_format($data->total_harga) }}</td>
        </tr>
        <tr>
            <td>PPN (10%)</td>
            <td>:</td>
            <td style="text-align: right">{{ number_format($ppn) }}</td>
        </tr>
        <tr>
            <td>DP</td>
            <td>:</td>
            <td style="text-align: right">{{ number_format($data->down_payment) }}</td>
        </tr>
        <tr style="font-weight: bold; background-color: #bdbdbd">
            <td>Total</td>
            <td>:</td>
            <td style="text-align: right">{{ number_format((($data->total_harga) + $ppn) - $data->down_payment) }}</td>
        </tr>
    </tbody>
</table>
</div>

<div class="clear"></div>

<div style="padding-top: 50px; float: right">
    <table>
        <tr>
            <th>Dibuat,</th>
            <th></th>
            <th>Menyetujui,</th>
        </tr>
        <tr>
            <td><br><br><br><br><br>(..................................................)</td>
            <td style="width: 30px">&nbsp;</td>
            <td><br><br><br><br><br>(..................................................)</td>
        </tr>
    </table>
</div>

@endsection

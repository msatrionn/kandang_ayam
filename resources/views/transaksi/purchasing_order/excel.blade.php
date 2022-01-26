@php
    header('Content-Transfer-Encoding: none');
    header("Content-type: application/vnd-ms-excel");
    header("Content-type: application/x-msexcel");
    header("Content-Disposition: attachment; filename=SUMMARY REPORT PURCHASING ORDER.xls");
@endphp
{{-- @extends('layouts.pdf')

@section('title', 'SUMMARY REPORT PURCHASING ORDER')
@section('periode', Tanggal::date($request->mulai_report) . ' - '. Tanggal::date($request->akhir_report) )

@section('header')
<style>
    .table-zebra tr:nth-child(even) {
       background-color: #f2f2f2;
    }
</style>
@endsection

@section('content') --}}
<table style="margin-bottom:20px">
    <tbody>
        <tr>
            <th colspan="13">SUMMARY REPORT PURCHASING ORDER</th>
        </tr>
        <tr>
            <th colspan="13">PERIODE {{ Tanggal::date($request->mulai_report) . ' - '. Tanggal::date($request->akhir_report) }}</th>
        </tr>
        <tr><td></td></tr>
        <tr>
            <td colspan="3">Jumlah Purchase Order</td>
            <td colspan="10">{{ ($resume['jumlah']) }} Unit</td>
        </tr>
        <tr>
            <td colspan="3">Total Nominal Order</td>
            <td colspan="10">Rp {{ ($resume['nominal']) }}</td>
        </tr>
        <tr><td></td></tr>
    </tbody>
</table>

<table class="table-zebra" width="100%" border="1">
    <thead>
        <tr>
            <th>#</th>
            <th>Tanggal</th>
            <th>Nomor PO</th>
            <th>Kandang</th>
            <th>Nama Produk</th>
            <th>Jumlah</th>
            <th>Terkirim</th>
            <th>Harga</th>
            <th>Total Harga</th>
            <th>PPN (%)</th>
            <th>Total Nominal</th>
            <th>Supplier</th>
            <th>Termin</th>
            <th>Operator</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $i => $row)
        <tr>
            <td>{{ ++$i }}</td>
            <td>{{ $row->tanggal }}</td>
            <td>{{ $row->nomor_purchasing }}</td>
            <td>{{ $row->kandang_id ? $row->kandang->nama : '-' }}</td>
            <td>
                <ul>
                    @foreach (json_decode($row->produk) as $item)
                    <li>{{ Produk::find($item->produk)->nama }}</li>
                    @endforeach
                </ul>
            </td>
            <td>
                <ul>
                    @foreach (json_decode($row->produk) as $item)
                        <li>{{ ($item->jumlah) }}</li>
                    @endforeach
                </ul>
            </td>
            <td>
                <ul>
                    @foreach (json_decode($row->produk) as $item)
                        <li>{{ ($item->terkirim) }}</li>
                    @endforeach
                </ul>
            </td>
            <td>
                <ul>
                    @foreach (json_decode($row->produk) as $item)
                        <li>{{ ($item->harga) }}</li>
                    @endforeach
                </ul>
            </td>
            <td>
                <ul>
                    @foreach (json_decode($row->produk) as $item)
                        <li>{{ ($item->harga * $item->jumlah) }}</li>
                    @endforeach
                </ul>
            </td>
            <td>
                <ul>
                    @foreach (json_decode($row->produk) as $item)
                        <li>{{ ($row->tax ? 0 : ($item->harga * $item->jumlah * (10/100))) }}</li>
                    @endforeach
                </ul>
            </td>
            <td>
                <ul>
                    @foreach (json_decode($row->produk) as $item)
                        <li>{{ (($item->harga * $item->jumlah ) + ($row->tax ? 0 : ($item->harga * $item->jumlah * (10/100)))) }}</li>
                    @endforeach
                </ul>
            </td>
            <td>{{ $row->termin }} Hari</td>
            <td>{{ $row->supplier->nama }}</td>
            <td>{{ $row->user->name }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
{{-- @endsection --}}

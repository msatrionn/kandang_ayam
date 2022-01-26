@extends('layouts.main')

@section('title', 'Report Laba Rugi')

@section('content')
<div class="card">
    <div class="card-body">
        <table class="table table-sm">
            <tr>
                <th>PENDAPATAN OPERASIONAL</th>
                <th></th>
            </tr>
            <tr>
                <th>&nbsp; &nbsp; &nbsp;Pendapatan</th>
                <th></th>
            </tr>
            <tr>
                <td>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;Penjualan Ayam</td>
                <td class="text-right">{{ number_format($result['penjualan_ayam'], 2) }}</td>
            </tr>
            <tr>
                <td>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;Penjualan Lain-Lain</td>
                <td class="text-right">{{ number_format($result['penjualan_lain'], 2) }}</td>
            </tr>
            <tr>
                <th>TOTAL PENDAPATAN OPERASIONAL</th>
                <th class="text-right">{{ number_format($result['penjualan_ayam'] + $result['penjualan_lain'], 2) }}</th>
            </tr>
            {{-- <tr>
                <th>HPP</th>
                <th></th>
            </tr>
            <tr>
                <td>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;Harga Pokok Penjualan</td>
                <td></td>
            </tr>
            <tr>
                <th>TOTAL HPP</th>
                <td class="text-right">{{ number_format($result['hpp'], 2) }}</td>
            </tr>
            <tr class="bg-light">
                <th>LABA KOTOR</th>
                <th></th>
            </tr> --}}
        </table>
    </div>
</div>
@endsection

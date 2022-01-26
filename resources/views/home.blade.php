@extends('layouts.main')

@section('title', 'Summary Dashboard')

@section('content')
<div class="row">

    <div class="col-lg-4 col-md-6">
        <div class="card">
            <div class="card-header">Informasi Kas</div>
            <div class="card-body">
                <div class="text-bold border-bottom p-1">
                    Kas Non Bank
                </div>
                {!! Option::info_kas(1) !!}
                <div class="text-bold border-bottom mt-2 p-1">
                    Kas di Bank
                </div>
                {!! Option::info_kas(2) !!}
            </div>
        </div>
    </div>

    <div class="col-lg-4 col-md-6">
        <div class="card">
            <div class="card-header">Summary Pemasukan Tahun {{ date('Y') }}</div>
            <div class="card-body">
                <div class="border-bottom p-1">
                    <div class="row">
                        <div class="col pr-1">Penjualan Ayam<br>({{
                            number_format($list_trans['penjualan_ayam']['terjual']) }} Ekor)</div>
                        <div class="col-auto pl-1"> {{ number_format($list_trans['penjualan_ayam']['nominal']) }}</div>
                    </div>
                </div>
                <div class="border-bottom p-1">
                    <div class="row">
                        <div class="col pr-1">Penjualan Lain</div>
                        <div class="col-auto pl-1"> {{ number_format($list_trans['penjualan_lain']['nominal']) }}</div>
                    </div>
                </div>
                <div class="border-bottom p-1">
                    <div class="row">
                        <div class="col pr-1">Setoran Modal</div>
                        <div class="col-auto pl-1"> {{ number_format($list_trans['setoran_modal']) }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">Jatuh Tempo Pembayaran</div>
            <div class="card-body">
                @foreach ($list_trans['jatuh_tempo'] as $row)
                <a href="{{ route('paypurchase.index', ['pay' => $row->id]) }}" class="text-secondary">
                    <div class="border-bottom p-1 cursor">
                        <div class="row">
                            <div class="col pr-1">{{ $row->nomor_purchasing }}</div>
                            <div class="col-auto pl-1"> {{ number_format(($row->total_harga + ($row->tax ? 0 :
                                ($row->total_harga * (10/100)))) - $row->dibayarkan) }}</div>
                        </div>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
    </div>

    <div class="col-lg-4 col-md-6">
        <div class="card">
            <div class="card-header">Summary Pengeluaran Tahun {{ date("Y") }}</div>
            <div class="card-body">
                @foreach ($list_trans['tipe_produk'] as $row)
                <div class="border-bottom p-1">
                    <div class="row">
                        <div class="col pr-1">{{ $row->nama }}</div>
                        <div class="col-auto pl-1"> {{ number_format($row->pengeluaran) }}</div>
                    </div>
                </div>
                @endforeach
                <div class="border-bottom p-1">
                    <div class="row">
                        <div class="col pr-1">Beban Angkut</div>
                        <div class="col-auto pl-1"> {{ number_format(Option::pengeluaran('beban_angkut')) }}</div>
                    </div>
                </div>
                <div class="border-bottom p-1">
                    <div class="row">
                        <div class="col pr-1">Biaya Kirim</div>
                        <div class="col-auto pl-1"> {{ number_format(Option::pengeluaran('biaya_kirim')) }}</div>
                    </div>
                </div>
                <div class="border-bottom p-1">
                    <div class="row">
                        <div class="col pr-1">Biaya Lain-Lain</div>
                        <div class="col-auto pl-1"> {{ number_format(Option::pengeluaran('biaya_lain_lain')) }}</div>
                    </div>
                </div>
                <div class="border-bottom p-1">
                    <div class="row">
                        <div class="col pr-1">Pembelian Tetap</div>
                        <div class="col-auto pl-1"> {{ number_format(Option::pengeluaran('pembelian_tetap')) }}</div>
                    </div>
                </div>
                <div class="border-bottom p-1">
                    <div class="row">
                        <div class="col pr-1">Pembelian Lain-Lain</div>
                        <div class="col-auto pl-1"> {{ number_format(Option::pengeluaran('pembelian_lain')) }}</div>
                    </div>
                </div>
                <div class="border-bottom p-1">
                    <div class="row">
                        <div class="col pr-1">Pengeluaran Lain-Lain</div>
                        <div class="col-auto pl-1"> {{ number_format($list_trans['keluar_lain']) }}</div>
                    </div>
                </div>
                <div class="border-bottom p-1">
                    <div class="row">
                        <div class="col pr-1">Tarik Modal</div>
                        <div class="col-auto pl-1"> {{ number_format($list_trans['tarik_modal']) }}</div>
                    </div>
                </div>
                <div class="border-bottom p-1">
                    <div class="row">
                        <div class="col pr-1">Gaji</div>
                        <div class="col-auto pl-1"> {{ number_format(Option::pengeluaran('gaji')) }}</div>
                    </div>
                </div>
                <div class="border-bottom p-1">
                    <div class="row">
                        <div class="col pr-1">Cashbon</div>
                        <div class="col-auto pl-1"> {{ number_format(Option::pengeluaran('cashbon')) }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- <div class="col-lg-4 col-md-6">
        <div class="card">
            <div class="card-header">10 Konsumen Transaksi Tertinggi</div>
            <div class="card-body">
                @foreach ($top10cust as $row)
                <div class="border-bottom p-1">
                    <div class="row">
                        <div class="col pr-1">
                            {{ $row->nama_konsumen }}
                        </div>
                        <div class="col-auto pl-1">
                            Rp {{ number_format($row->total) }}
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div> --}}

    {{-- <div class="col-lg-4 col-md-6">
        <div class="card">
            <div class="card-header">10 Supplier Transaksi Tertinggi</div>
            <div class="card-body">
                @foreach ($top10sup as $row)
                <div class="border-bottom p-1">
                    <div class="row">
                        <div class="col pr-1">
                            {{ $row->nama }}
                        </div>
                        <div class="col-auto pl-1">
                            Rp {{ number_format($row->total) }}
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div> --}}

    {{-- <div class="col-lg-4 col-md-6">
        <div class="card">
            <div class="card-header">Stock Produk Limit</div>
            <div class="card-body">
                @foreach ($produk as $row)
                @if ($row->stocklimit >= $row->jumlah_stock)
                <div class="border-bottom p-1">
                    <div class="row">
                        <div class="col pr-1">
                            {{ $row->nama }}
                        </div>
                        <div class="col-auto pl-1">
                            {{ number_format($row->jumlah_stock) }} {{ $row->tipesatuan->nama }}
                        </div>
                    </div>
                </div>
                @endif
                @endforeach
            </div>
        </div>
    </div> --}}
</div>
@endsection

@section('header')
<link rel="stylesheet" href="{{ asset('js/vendor/politespace/politespace.css') }}">
@endsection

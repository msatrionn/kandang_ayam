@extends('layouts.main')

@section('title', 'Report Arus Barang')

@section('content')
<div class="card">
    <div class="card-body">
        <div class="accordion" id="accordionData">
            <div class="card">
                {{-- Header::jml_trans_head($data->id) --}}
                @foreach ($data as $row)
                <div class="card-header" id="heading{{ $row->id }}">
                    <p class="mb-0">
                        <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse"
                            data-target="#collapse{{ $row->id }}" aria-expanded="true"
                            aria-controls="collapse{{ $row->id }}">
                            {{-- {{ $row->tanggal }} || {{ $row->nama_konsumen }} ||
                            Total : {{ Header::jml_trans_head($row->id) }} ekor --}}
                            {{-- Angkatan {{ $row->id }} | {{ $row->id }} --}}
                        </button>
                    </p>
                </div>

                <div id="collapse{{ $row->id }}" class="collapse show" aria-labelledby="heading{{ $row->id }}"
                    data-parent="#accordionData">
                    <div class="card-body">
                        @php
                        $total_masuk = 0 ;
                        $pakan = 0 ;
                        @endphp

                        <div class="row">
                            <div class="col-md">
                                <div class="table-responsive">
                                    <table class="table table-sm table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th>Tanggal</th>
                                                <th>Jenis</th>
                                                <th>Jumlah barang</th>
                                                <th>Harga satuan</th>
                                                <th>Nominal</th>
                                                <th>Keterangan</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>{{ $row->tanggal }}</td>
                                                @foreach (json_decode($row->produk) as $item)
                                                @php
                                                $namaProduk=$item->produk;
                                                $qtyProduk=$item->jumlah;
                                                $hargaProduk=$item->harga;
                                                @endphp
                                                @endforeach
                                                <td>{{ Produk::getProduk($namaProduk) }}</td>
                                                <td>{{ $qtyProduk }}</td>
                                                <td>{{ $hargaProduk }}</td>
                                                <td>{{ $hargaProduk*$qtyProduk }}</td>
                                                <td>{{ $row->keterangan }}</td>
                                            </tr>
                                            {{-- @php
                                            $total_harga=0
                                            @endphp

                                            @foreach ($row->list_trans as $i => $rows)
                                            @php

                                            $hari = date_diff(date_create($rows->tanggal ?? ""),
                                            date_create($rows->riwayat->tanggal ?? ""));
                                            // dd($rows->tanggal)
                                            $total_harga+=($rows->total_harga*($hari->d +
                                            1));
                                            @endphp --}}
                                            {{-- <tr> --}}
                                                {{-- <td style="text-align: right">{{ $rows->qty }} Ekor</td>
                                                <td>Rp. {{ number_format($rows->harga_satuan, 0 , 2) }}</td>
                                                <td>{{ $hari->d + 1 }} Hari</td>
                                                <td>Rp. {{ number_format(($rows->total_harga*($hari->d +
                                                    1)),0,2) }}</td> --}}
                                                {{--
                                            </tr> --}}
                                            {{-- @endforeach --}}
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th colspan="3">Total</th>
                                                <th colspan="3" class="text-right">
                                                    {{-- Rp {{ number_format($total_harga) }} --}}
                                                </th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
                {{ $data->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

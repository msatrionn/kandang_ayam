@extends('layouts.main')

@section('title', 'Report Arus Barang')

@section('content')
<div class="card">
    <div class="card-body">
        <div class="accordion" id="accordionData">
            <div class="card">
                @foreach ($angkatan as $row)
                <div class="card-header" id="heading{{ $row->no }}">
                    <p class="mb-0">
                        <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse" data-target="#collapse{{ $row->no }}" aria-expanded="true" aria-controls="collapse{{ $row->no }}">
                        Angkatan {{ $row->no }} | {{ $row->id }}
                        </button>
                    </p>
                </div>

                <div id="collapse{{ $row->no }}" class="collapse show" aria-labelledby="heading{{ $row->no }}" data-parent="#accordionData">
                    <div class="card-body">
                        @php
                            $total_masuk    =   0 ;
                            $pakan          =   0 ;
                        @endphp

                        <div class="row">
                            <div class="col-md">
                                <div class="table-responsive">
                                    <table class="table table-sm table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th>Tanggal</th>
                                                <th>Jenis</th>
                                                <th>Nama</th>
                                                <th>Qty</th>
                                                <th>Satuan</th>
                                                <th>Nominal</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach (Stokkandang::where('jumlah', '>', 0)->orderBy('tanggal', 'ASC')->whereIn('kandang_id', Riwayat::select('kandang')->where('angkatan', $row->id))->get() as $list)
                                            @php
                                                if ($list->tipe == 2) {
                                                    $pakan  +=  $list->jumlah ;
                                                }
                                            @endphp
                                            <tr>
                                                <td>{{ date('d/m/Y', strtotime($list->tanggal)) }}</td>
                                                <td>{{ $list->produk->tipeset->nama }}</td>
                                                <td>{{ $list->produk->nama }}</td>
                                                <td>{{ $list->jumlah }}</td>
                                                <td>{{ $list->produk->tipesatuan->nama }}</td>
                                                <td class="text-right">
                                                    @if ($list->stok->delivery)
                                                    Rp {{ number_format(($list->stok->delivery->purchasing->total_harga / $list->stok->delivery->purchasing->terkirim) * $list->jumlah) }}
                                                    @php
                                                        $total_masuk    +=  ($list->stok->delivery->purchasing->total_harga / $list->stok->delivery->purchasing->terkirim) * $list->jumlah ;
                                                    @endphp
                                                    @else
                                                    Cut Off
                                                    @endif
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th colspan="3">Total</th>
                                                <th colspan="3" class="text-right">Rp {{ number_format($total_masuk) }}</th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>

                            <div class="col-md">
                                {{--  --}}
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection

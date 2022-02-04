<div class="card-body">
    <div class="row">
        <div class="col-md">
            <div class="table-responsive">
                <table class="table table-sm table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Jenis</th>
                            <th>Jumlah barang</th>
                            <th>Satuan</th>
                            <th>Nominal</th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                        $total_masuk = 0 ;
                        $jumlah = 0 ;
                        $satuan = 0 ;
                        $total = 0 ;
                        @endphp
                        @foreach (Stokkandang::where('jumlah', '>', 0)
                        ->join('riwayatkandang','riwayatkandang.kandang','stock_kandang.kandang_id')
                        ->orderBy('stock_kandang.tanggal',
                        'ASC')->where('tipe',1)->whereIn('kandang_id',
                        Riwayat::select('kandang'))->get() as $list)
                        <tr>
                            <td>{{ date('d/m/Y', strtotime($list->tanggal)) }}</td>
                            {{-- <td>{{ $list->produk->tipeset->nama }}</td> --}}
                            <td>{{ $list->produk->nama }}</td>
                            <td>{{ $list->jumlah }}</td>
                            <td>{{ $list->produk->tipesatuan->nama }}</td>
                            <td class="text-right">
                                @if ($list->stok->delivery)
                                @php
                                $total_masuk=($list->stok->delivery->purchasing->total_harga /
                                $list->stok->delivery->purchasing->terkirim) * $list->jumlah
                                @endphp
                                Rp {{ number_format($total_masuk,0,2) }}
                                @php
                                $total_masuk += ($list->stok->delivery->purchasing->total_harga /
                                $list->stok->delivery->purchasing->terkirim) *
                                $list->jumlah ;
                                @endphp
                                @else
                                Cut Off
                                @endif
                            </td>
                            <td>{{ $list->nama_kandang->nama ?? "" }}</td>
                        </tr>
                        @php
                        $jumlah += $list->jumlah;
                        $total += $total_masuk;
                        @endphp
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="2">Total</th>
                            <th colspan="2">{{ $jumlah }}</th>
                            <th colspan="3">
                                Rp. {{ number_format($total,0,2) }}
                            </th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>

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
                            <th>Harga satuan</th>
                            <th>Nominal</th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                        $jumlah=0;
                        $satuan=0;
                        $total=0;
                        @endphp
                        @foreach (LogTrans::where('produk_id','!=',23)->where('produk_id','!=',78)->get() as $list)
                        <tr>
                            <td>{{ date('d/m/Y', strtotime($list->tanggal)) }}</td>
                            {{-- <td>{{ $list->produk->tipeset->nama }}</td> --}}
                            <td>{{ $list->produk->nama }}</td>
                            <td class="text-right">
                                {{ $list->qty }}
                            </td>

                            <td>{{ $list->produk->tipesatuan->nama }}</td>
                            <td class="text-right">
                                Rp. {{ number_format($list->nominal/$list->qty,0,2) }}
                            </td>
                            <td class="text-right">
                                Rp. {{ number_format($list->nominal,0,2) }}
                            </td>
                            <td>{{ $list->nama_kandang->nama ?? "" }}</td>
                        </tr>
                        @php
                        $jumlah+=$list->qty;
                        $satuan+=$list->nominal/$list->qty;
                        $total+=$list->nominal;
                        @endphp
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="2">Total</th>
                            <th colspan="2">{{ $jumlah}}</th>
                            <th colspan="1">Rp. {{ number_format($satuan,0,2) }}</th>
                            <th colspan="2">
                                Rp. {{ number_format($total,0,2) }}
                            </th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>

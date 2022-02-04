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
                        @foreach (LogTrans::where('produk_id',78)->get() as $item)
                        <tr>
                            <td>{{ $item->tanggal }}</td>
                            <td>{{ $item->produk->nama }}</td>
                            <td>{{ $item->qty }}</td>
                            <td>{{ $item->nominal/$item->qty }}</td>
                            <td>{{ $item->nominal}}</td>
                            <td>{{ $item->nama_kandang->nama }}</td>
                        </tr>
                        @php
                        $jumlah+=$item->qty;
                        $satuan+=$item->nominal/$item->qty;
                        $total+=$item->nominal;
                        @endphp
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="2">Total</th>
                            <th colspan="1">{{ $jumlah }}</th>
                            <th colspan="1">Rp. {{ number_format($satuan,0,2) }}</th>
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

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
                        $totalqty=0;
                        $totalsatuan=0;
                        $total=0;
                        @endphp
                        @foreach ($doc as $item)
                        <tr>
                            <td>{{ $item->headtrans->tanggal }}</td>
                            <td>{{ $item->produk->nama }}</td>
                            <td>{{ $item->qty }}</td>
                            <td>{{ $item->produk->tipesatuan->nama ?? "" }}</td>
                            <td>Rp. {{ number_format($item->harga_satuan,0,2) }}</td>
                            <td>Rp. {{ number_format($item->total_harga,0,2) }}</td>
                            <td>{{ $item->kandang->nama ?? "" }}</td>
                        </tr>
                        @php
                        $totalqty+=$item->qty;
                        $totalsatuan+=$item->harga_satuan;
                        $total+=$item->total_harga;
                        @endphp
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="2">Total</th>
                            <th colspan="2">{{ $totalqty }}</th>
                            <th colspan="1">Rp. {{ number_format($totalsatuan,0,2) }} </th>
                            <th colspan="2">Rp. {{ number_format($total,0,2) }} </th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>

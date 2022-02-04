<div class="card-body">
    <div class="row">
        <div class="col-md">
            <div class="table-responsive">
                <table class="table table-sm table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Nama</th>
                            <th>Jumlah barang</th>
                            <th>Harga satuan</th>
                            <th>Nominal</th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                        $total=0;
                        @endphp
                        @foreach (Gaji::with('karyawan')->get() as $item)
                        <tr>
                            <td>{{ $item->tanggal }}</td>
                            <td>{{ $item->karyawan->name }}</td>
                            <td>x</td>
                            <td>x</td>
                            <td>Rp. {{ number_format($item->total_didapat,0,2) }}</td>
                            <td>{{ Option::where('id',$item->kandang_id)->first()->nama ?? "" }}</td>
                        </tr>
                        @php
                        $total+=$item->total_didapat;
                        @endphp
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="4">Total</th>
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

<div class="card">
    <div class="card-body">
        <a href="{{ route('pembelian.index') }}" class="btn btn-primary mb-3">Kembali</a>
        <table class="table table-sm">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Produk</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($produk as $i => $row)
                <tr>
                    <td>{{ ++$i }}</td>
                    <td>{{ $row->nama }}</td>
                    <td class="text-right">
                        <button type="button" data-id="{{ $row->id }}" class="btn btn-sm ubahdata py-0 px-2 {{ $row->tipe ? 'btn-success' : 'btn-info' }}">Pengeluaran {{ $row->tipe ? 'Tetap' : 'Lain-Lain' }}</button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

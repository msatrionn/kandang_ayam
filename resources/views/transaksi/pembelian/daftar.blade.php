@foreach ($riwayat as $row)
<div class="border mb-2 p-2 rounded cursor" data-toggle="modal" data-target="#modals{{ $row->id }}">
    <div class="row">
        <div class="col pr-1">{{ $row->produk->nama }}<br>{{ number_format($row->qty) }} {{ $row->produk->tipesatuan->nama }}<br><span class='text-success'>{{ $row->kandang_id ? Option::find($row->kandang_id)->nama : "" }}</span></div>
        <div class="col-auto text-right pl-1">{{ Tanggal::date($row->tanggal) }}<br>Rp {{ number_format($row->nominal) }}</div>
    </div>
</div>

<div class="modal fade" id="modals{{ $row->id }}" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="modals{{ $row->id }}Label" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modals{{ $row->id }}Label">Detail Pembelian</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-sm">
                    <tbody>
                        <tr>
                            <td>Produk</td>
                            <td>{{ $row->produk->nama }}</td>
                        </tr>
                        <tr>
                            <td>Jumlah Pembelian</td>
                            <td>{{ number_format($row->qty) }} {{ $row->produk->tipesatuan->nama }}</td>
                        </tr>
                        <tr>
                            <td>Harga Pembelian</td>
                            <td>Rp {{ number_format($row->nominal) }}</td>
                        </tr>
                        <tr>
                            <td>Tanggal Transaksi</td>
                            <td>{{ Tanggal::date($row->tanggal) }}</td>
                        </tr>
                        <tr>
                            <td>Metode Pembayaran</td>
                            <td>{{ $row->method->nama }}</td>
                        </tr>
                    </tbody>
                </table>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                @if ($row->status == 1)
                <form action="{{ route('pembelian.destroy') }}" method="post">
                    @csrf @method('delete') <input type="hidden" name="x_code" value="{{ $row->id }}">
                    <button class="btn btn-danger float-right">Hapus</button>
                </form>
                @endif
            </div>
        </div>
    </div>
</div>
@endforeach

<div id="daftar_paginate">
    {{ $riwayat->appends($_GET)->onEachSide(0)->links() }}
</div>

<script>
$(".pagination").attr('class', 'pagination pagination-sm pt-2');
</script>

<script>
$('#daftar_paginate .pagination a').on('click', function(e) {
    e.preventDefault();

    url = $(this).attr('href') ;
    $.ajax({
        url: url,
        method: "GET",
        success: function(response) {
            $('#daftar_pembelian').html(response);
        }

    });
});
</script>

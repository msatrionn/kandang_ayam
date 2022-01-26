@foreach ($data as $row)
<div class="border rounded p-2 mb-2">
    <div class="row">
        <div class="col pr-1">
            <small>Produk</small><br>
            {{ $row->produk->nama }} <span class="text-success">{{ $row->kandang_id ? '(' . $row->kandang->nama . ')' : '' }}</span><br>
            {{ $row->qty }} {{ $row->produk->tipesatuan->nama ?? '' }} (@ Rp {{ number_format($row->total_harga / $row->qty) }})
        </div>
        <div class="col-auto pl-1 text-right">
            <small>Total Harga</small><br>
            <b>{{ number_format($row->total_harga) }}</b><br>
            <div class="text-danger cursor hapus_list" data-id="{{ $row->id }}">
                <i class="fa fa-trash"></i> Batalkan
            </div>
        </div>
    </div>
</div>
@endforeach

<div class="row mb-2">
    <div class="col-md pr-1" id="daftar_paginate">
        {{ $data->appends($_GET)->onEachSide(0)->links() }}
    </div>
    <div class="col-md pl-1 text-right">
        Total Transaksi<br><b>Rp {{ number_format($total) }}</b>
    </div>
</div>

<script>
$(".pagination").attr('class', 'pagination pagination-sm pt-2');
$('#daftar_paginate .pagination a').on('click', function(e) {
    e.preventDefault();

    url = $(this).attr('href') ;
    $.ajax({
        url: url,
        method: "GET",
        success: function(response) {
            $('#daftar_list').html(response);
        }

    });
});
</script>

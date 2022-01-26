<div class="card">
    <div class="card-header">
        Riwayat Mutasi
    </div>
    <div class="card-body">
        @foreach ($data as $row)
        <div class="border rounded p-2 mb-2">
            <div class="row">
                <div class="col pr-1">
                    {{ $row->produk->nama }}<br>
                    {{ Tanggal::date($row->tanggal) }}
                </div>
                <div class="col-auto pl-1 text-right">
                    {{ number_format($row->jumlah) }} {{ $row->produk->tipesatuan->nama }}<br>
                    {{ $row->kandang->nama }}
                </div>
            </div>
        </div>
        @endforeach

        <div id="daftar_paginate">
            {{ $data->appends($_GET)->onEachSide(0)->links() }}
        </div>
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
            $('#riwayat').html(response);
        }

    });
});
</script>


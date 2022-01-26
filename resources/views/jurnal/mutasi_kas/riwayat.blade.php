@foreach ($mutasi as $row)
<div class="border rounded mb-2 p-2">
    <div class="row">
        <div class="col pr-1">
            {{ Tanggal::date($row->tanggal) }}<br>
            {{ $row->method->nama }} -> {{ $row->child->method->nama }}
        </div>
        <div class="col-auto text-right pl-1">
            Rp {{ number_format($row->payment) }}<br>
            <i class="fa fa-trash text-danger" id="hapus_mutasi" data-id="{{ $row->id }}"></i>
        </div>
    </div>
</div>
@endforeach

<div id="daftar_paginate">
    {{ $mutasi->appends($_GET)->onEachSide(0)->links() }}
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
            $('#riwayat_mutasi').html(response);
        }

    });
});
</script>

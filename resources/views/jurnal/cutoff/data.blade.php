@foreach ($data as $row)
<div class="border rounded mb-2 p-2">
    <div class="row">
        <div class="col pr-1"><span class="text-bold text-primary">[{{ $row->tipeset->nama }}]</span> {{ $row->produk->nama }}</div>
        <div class="col-auto text-right pl-1">{{ Tanggal::date($row->tanggal) }}</div>
    </div>
    <div class="row">
        <div class="col pr-1">{{ number_format($row->qty_awal) }} {{ $row->produk->tipesatuan->nama }}</div>
        <div class="col-auto text-right pl-1">
            @if ($row->qty_awal == $row->stock_opname)
            <i class="fa fa-trash text-danger hapus_cutoff cursor" data-id="{{ $row->id }}"></i> &nbsp;
            <i class="fa fa-edit text-primary cursor" data-toggle="modal" data-target="#modalCutoff{{ $row->id }}"></i>
            @endif
        </div>
    </div>
</div>

<div class="modal fade" id="modalCutoff{{ $row->id }}" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="modalCutoff{{ $row->id }}Label" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalCutoff{{ $row->id }}Label">Ubah Data Cut Off</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    Item Produk
                    <div>{{ $row->produk->nama }}</div>
                </div>
                <div class="row">
                    <div class="col pr-1">
                        <div class="form-group">
                            Jumlah
                            <input type="number" value="{{ $row->qty_awal }}" id="jumlah_ubah{{ $row->id }}" class="form-control">
                        </div>
                    </div>
                    <div class="col pl-1">
                        <div class="form-group">
                            Tanggal
                            <input type="date" value="{{ $row->tanggal }}" id="tanggal_ubah{{ $row->id }}" class="form-control">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="ubah_data" data-id="{{ $row->id }}">Ubah</button>
            </div>
        </div>
    </div>
</div>
@endforeach

<div id="daftar_paginate">
    {{ $data->appends($_GET)->onEachSide(0)->links() }}
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
            $('#data_cutoff').html(response);
        }

    });
});
</script>


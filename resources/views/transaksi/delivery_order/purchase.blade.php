@foreach ($purchase as $row)
<div class="p-2 border rounded mb-2">
    <div class="row mb-2 font-weight-bold">
        <div class="col text-primary text-capitalize">
            {{ $row->nomor_purchasing }}
        </div>
        <div class="col-auto">{{ Tanggal::date($row->tanggal) }}</div>
    </div>

    @foreach (json_decode($row->produk) as $list)
    <div class="radio-toolbar">
        <div>
            <input type="radio" name="purchase" class="purchase" @if ($list->jumlah == $list->terkirim) disabled @endif
            data-nomor="{{ $row->id }}" data-produk="{{ $list->produk }}" value="{{ $list->id }}" id="purchase{{
            $list->id }}">
            <label for="purchase{{ $list->id }}"
                class="@if ($list->jumlah == $list->terkirim) bg-info text-light @endif">
                <div class="row">
                    <div class="col pr-1">{{ Produk::find($list->produk)->nama }}</div>
                    <div class="col-auto pl-1">{{ number_format($list->jumlah) }} | {{ number_format($list->terkirim) }}
                    </div>
                </div>
            </label>
        </div>
    </div>
    @endforeach
</div>

@endforeach

<div id="purchse_paginate" class="mt-2">
    {{ $purchase->appends($_GET)->onEachSide(0)->links() }}
</div>

<script>
    $(".pagination").attr('class', 'pagination pagination-sm pt-2');
</script>

<script>
    $('#purchse_paginate .pagination a').on('click', function(e) {
        e.preventDefault();
        var url = $(this).attr('href');
        $.ajax({
            url: url,
            method: "GET",
            success: function(response) {
                $('#data_purchase').html(response);
            }

        });
    });

</script>

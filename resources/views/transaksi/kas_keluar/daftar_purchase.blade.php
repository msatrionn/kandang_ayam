@if (COUNT($data))
    @foreach ($data as $item)
    <div class="radio-toolbar">
        <div>
            <input type="radio" name="purchase" class="purchase" value="{{ $item->id }}" id="purchase{{ $item->id }}" {{ ($request->pay == $item->id) ? 'checked' : '' }}>
            <label for="purchase{{ $item->id }}">
                <div class="row">
                    <div class="col text-primary text-capitalize">{{ $item->nomor_purchasing }}</div>
                    <div class="col-auto">{{ Tanggal::date($item->tanggal) }}</div>
                </div>
                <div class="row">
                    <div class="col">
                        DP : Rp {{ number_format($item->down_payment) }}
                    </div>
                    <div class="col-auto">Rp {{ number_format((($item->total_harga) + ($item->tax ? 0 : ($item->total_harga) * (10/100)) - $item->down_payment) - ($item->dibayarkan ?? 0)) }}</div>
                </div>
            </label>
        </div>
    </div>
    @endforeach

    <div id="purchase_paginate">
        {{ $data->appends($_GET)->onEachSide(0)->links() }}
    </div>

    <script>
    $(".pagination").attr('class', 'pagination pagination-sm pt-2');
    </script>

    <script>
        $('#purchase_paginate .pagination a').on('click', function(e) {
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
@else
    TIDAK ADA PURCHASE ORDER
@endif

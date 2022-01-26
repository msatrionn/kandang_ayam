@if (COUNT($trans))
<div class="card">
    <div class="card-header">History Report Pembayaran Purchase Order</div>
    <div class="card-body">
        @foreach ($trans as $row)
            <div class="border rounded mb-2 p-2">
                <div class="row">
                    <div class="col pr-1">
                        {{ $row->purchase->nomor_purchasing }}
                    </div>
                    <div class="col-auto pl-1">Rp {{ number_format($row->nominal) }}</div>
                </div>
                <div class="row">
                    <div class="col pr-1">
                        <b>{{ Tanggal::date($row->tanggal) }}</b>
                        <div class="small">{{ $row->purchase->supplier->nama }}</div>
                    </div>
                    <div class="col-auto text-right pl-1">
                        {{ $row->method->nama ?? '' }}
                        <div>
                        @if ($row->status == 1)
                        <button class="btn btn-link p-0 mx-2" data-toggle="modal" data-target="#purchase{{ $row->id }}"><i class="fa fa-edit"></i></button>
                        @endif

                        <a href="{{ route('paypurchase.pdf', $row->id) }}">
                            <i class="fa fa-file-pdf-o text-danger"></i>
                        </a>
                        </div>
                    </div>
                </div>
            </div>

            @if ($row->status == 1)
            <div class="modal fade" id="purchase{{ $row->id }}" tabindex="-1" aria-labelledby="purchase{{ $row->id }}Label" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="purchase{{ $row->id }}Label">Ubah Pembayaran Purchase Order</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col pr-1">{{ $row->purchase->nomor_purchasing }}</div>
                                <div class="col-auto pl-1">Rp {{ number_format($row->nominal) }}</div>
                            </div>
                            <div class="row border-bottom pb-3 mb-2">
                                <div class="col pr-1">
                                    {{-- {{ $row->purchase->product->nama }} --}}
                                    <div class="small">{{ $row->purchase->supplier->nama }}</div>
                                </div>
                                <div class="col-auto pl-1">
                                    {{ $row->method->nama ?? '' }}
                                </div>
                            </div>

                            <div class="form-group">
                                Tanggal Bayar
                                <input type="date" name="tanggal_bayar" class="form-control" id="tanggal_bayar{{ $row->id }}" value="{{ $row->tanggal }}" autocomplete="off">
                            </div>

                            <div class="form-group">
                                Nominal Dibayarkan
                                <input type="numeric" class="form-control" id="nominal_dibayarkan{{ $row->id }}" value="{{ $row->nominal }}" data-politespace data-politespace-grouplength="3" data-politespace-delimiter="," data-politespace-decimal-mark="." step="0.01" data-politespace-reverse>
                            </div>

                            <div class="form-group">
                                Metode Bayar
                                <select name="metode_bayar" data-placeholder="Pilih Metode Pembayaran" class="form-control" id="metode_bayar{{ $row->id }}">
                                    <option value=""></option>
                                    @foreach ($pay as $id => $item)
                                    <option value="{{ $id }}" {{ ($row->kas == $id) ? 'selected' : '' }}>{{ $item }}</option>
                                    @endforeach
                                </select>
                                <div id="errPay"></div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary ubah_data" data-id="{{ $row->id }}">Ubah</button>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        @endforeach

        <div id="riwayat_paginate">
            {{ $trans->appends($_GET)->onEachSide(0)->links() }}
        </div>
    </div>
</div>


<script>
    $('#riwayat_paginate .pagination a').on('click', function(e) {
        e.preventDefault();
        var url = $(this).attr('href');
        $.ajax({
            url: url,
            method: "GET",
            success: function(response) {
                $('#history_report').html(response);
            }

        });
    });

</script>

<script>
$(".pagination").attr('class', 'pagination pagination-sm pt-2');
</script>

<script>
    $(function () {
        $('select').each(function () {
            $(this).select2({
            theme: 'bootstrap4',
            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
            placeholder: $(this).data('placeholder'),
            allowClear: Boolean($(this).data('allow-clear')),
            closeOnSelect: !$(this).attr('multiple'),
            });
        });
    });
</script>

@endif

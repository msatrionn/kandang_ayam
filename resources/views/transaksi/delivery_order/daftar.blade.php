@if (COUNT($delivery))
    <div class="mt-3">
        @foreach ($delivery as $row)
        <div class="border border-dark rounded cursor mb-2 p-2" data-toggle="modal" data-target="#deliv{{ $row->id }}">
            <div class="row">
                <div class="col pr-1">
                    {{ $row->produk->nama }}
                </div>
                <div class="col-auto pl-1">{{ $row->purchasing->nomor_purchasing }}</div>
            </div>
            <div class="row">
                <div class="col pr-1">{{ Tanggal::date($row->tanggal) }}</div>
                <div class="col-auto pl-1">{{ number_format($row->qty) }} {{ $row->produk->tipesatuan->nama }}</div>
            </div>
        </div>

        <div class="modal fade" id="deliv{{ $row->id }}" tabindex="-1" aria-labelledby="deliv{{ $row->id }}Label" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deliv{{ $row->id }}Label">Ubah Input Data Delivery Order</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-6 pr-1">
                                <div class="form-group">
                                    Tanggal
                                    <input type="date" name="tanggal" class="form-control" id="tanggal{{ $row->id }}" value="{{ $row->tanggal }}" placeholder="Tuliskan Tanggal Kirim" autocomplete="off">
                                </div>
                            </div>
                            <div class="col-6 pl-1">
                                <div class="form-group">
                                    Jumlah Kirim
                                    <input type="number" name="jumlah_kirim" class="form-control" id="jumlah_kirim{{ $row->id }}" value="{{ $row->qty }}" placeholder="Tuliskan Jumlah Kirim" autocomplete="off" data-politespace data-politespace-grouplength="3" data-politespace-delimiter="," data-politespace-decimal-mark="." step="0.01" data-politespace-reverse>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6 pr-1">
                                <div class="form-group">
                                    Biaya Kirim
                                    <input type="number" name="biaya_kirim" class="form-control" @if(LogTrans::where('table', 'delivery')->where('table_id', $row->id)->where('jenis', 'biaya_kirim')->where('status', 2)->count()) disabled @endif value="{{ $row->biaya_pengiriman }}" id="biaya_kirim{{ $row->id }}" placeholder="Tuliskan Biaya Kirim" autocomplete="off" data-politespace data-politespace-grouplength="3" data-politespace-delimiter="," data-politespace-decimal-mark="." step="0.01" data-politespace-reverse>
                                </div>
                            </div>
                            <div class="col-6 pl-1">
                                <div class="form-group">
                                    Biaya Beban Angkut
                                    <input type="number" name="biaya_beban_angkut" class="form-control" @if(LogTrans::where('table', 'delivery')->where('table_id', $row->id)->where('jenis', 'beban_angkut')->where('status', 2)->count()) disabled @endif value="{{ $row->beban_angkut }}" id="biaya_beban_angkut{{ $row->id }}" placeholder="Tuliskan Biaya Beban Angkut" autocomplete="off" data-politespace data-politespace-grouplength="3" data-politespace-delimiter="," data-politespace-decimal-mark="." step="0.01" data-politespace-reverse>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            Biaya Lain-Lain
                            <input type="number" name="biaya_lain_lain" class="form-control"  @if(LogTrans::where('table', 'delivery')->where('table_id', $row->id)->where('jenis', 'biaya_lain_lain')->where('status', 2)->count()) disabled @endif value="{{ $row->biaya_lain }}" id="biaya_lain{{ $row->id }}" placeholder="Nominal Biaya Lainnya" autocomplete="off" data-politespace data-politespace-grouplength="3" data-politespace-delimiter="," data-politespace-decimal-mark="." step="0.01" data-politespace-reverse>
                        </div>

                        <div class="form-group">
                            Metode Bayar
                            <select name="metode_bayar" data-placeholder="Pilih Metode bayar" @if (LogTrans::where('table', 'delivery')->where('table_id', $row->id)->where('jenis', 'biaya_kirim')->where('status', 2)->count() AND  LogTrans::where('table', 'delivery')->where('table_id', $row->id)->where('jenis', 'beban_angkut')->where('status', 2)->count()) disabled @endif id="metode_bayar{{ $row->id }}" class="form-control">
                                <option value=""></option>
                                @foreach ($payment as $id => $item)
                                <option value="{{ $id }}" {{ ($row->kas == $id) ? 'selected' : '' }}>{{ $item }}</option>
                                @endforeach
                            </select>
                            <div id="errPayment"></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary ubah_delivery" data-id="{{ $row->id }}">Ubah</button>
                    </div>
                </div>
            </div>
        </div>
        @endforeach

        <div id="daftar_paginate">
            {{ $delivery->appends($_GET)->onEachSide(0)->links() }}
        </div>
    </div>

    <script>
    $(".pagination").attr('class', 'pagination pagination-sm pt-2');
    </script>

    <script>
        $('#daftar_paginate .pagination a').on('click', function(e) {
            e.preventDefault();
            var url = $(this).attr('href');
            $.ajax({
                url: url,
                method: "GET",
                success: function(response) {
                    $('#data_daftar').html(response);
                }

            });
        });
    </script>

@endif




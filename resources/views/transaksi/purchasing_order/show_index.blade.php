@foreach ($data as $row)
<div class="border rounded p-2 mb-2">
    <div class="row">
        <div class="col pr-1">
            <span class="text-success cursor" data-toggle="collapse" data-target="#collapse{{ $row->id }}"
                aria-expanded="true" aria-controls="collapse{{ $row->id }}">{{ $row->nomor_purchasing }}</span>
            @if ($row->terkirim)
            <span class="small text-danger">[Delivery {{ number_format($row->terkirim) }}]</span>
            @endif
        </div>
        <div class="col-auto pl-1">{{ Tanggal::date($row->tanggal) }}</div>
    </div>
    <div class="row">
        <div class="col pr-1">Rp {{ number_format($row->total_harga + ($row->tax ? 0 :
            $row->total_harga * (10/100))) }} @if ($row->tax) - <b class="text-primary">Include
                PPN</b> @endif</div>
        <div class="col-auto pl-1">
            @if (COUNT($row->antar) < 1) <form action="{{ route('purchasing.destroy') }}" method="POST">
                @csrf @method('delete') <input type="hidden" name="x_code" value="{{ $row->id }}"">
                            @endif
                                <a href=" {{ route('purchasing.detailpdf', $row->id) }}" class="btn btn-link p-0 pr-2
                text-dark"><i class="fa fa-file-pdf-o"></i></a>
                <button class="btn btn-link" type="button" data-toggle="collapse"
                    data-target="#collapseExample{{ $row->id }}" aria-expanded="false"
                    aria-controls="collapseExample"><i class="fa fa-info"></i></button>
                @if (COUNT($row->antar) < 1) @if ($row->status == 1)
                    <button type="submit" class="btn btn-link p-0 pl-2 text-danger"><i class="fa fa-trash"></i></button>
                    @endif
                    </form>
                    @endif
        </div>
    </div>
</div>

<div id="collapse{{ $row->id }}" class="collapse border p-2 mb-3" aria-labelledby="heading{{ $row->id }}"
    data-parent="#accordionData">
    @foreach (Kirim::where('purchase_id', $row->id)->get() as $item)
    <div class="border-bottom mb-1">
        <div class="row">
            <div class="col pr-1">
                {{ Tanggal::date($item->tanggal) }}
            </div>
            <div class="col text-right pl-1">{{ number_format($item->qty) }}</div>
        </div>
    </div>
    @endforeach
</div>

{{-- <div class="modal fade" id="modal{{ $row->id }}" tabindex="-1" aria-labelledby="modal{{ $row->id }}Label"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div> --}}
<div class="show">
    <div class="collapse" id="collapseExample{{ $row->id }}">
        <div class="card card-body">
            <div class="modal-header">
                <h5 class="modal-title" id="modal{{ $row->id }}Label">Detail Purchasing Order</h5>

            </div>
            <div class="modal-body">
                <div class="form-group">
                    <div class="small">Kandang</div>
                    {{ $row->kandang_id ? $row->kandang->nama : '-' }}
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <div class="small">Nomor Purchasing</div>
                            {{ $row->nomor_purchasing }}
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <div class="small">Tanggal Purchase</div>
                            {{ Tanggal::date($row->tanggal) }}
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <div class="small">Termin</div>
                            {{ number_format($row->termin) }} Hari
                        </div>
                    </div>
                </div>

                <div class="border-bottom font-weight-bold p-1">
                    <div class="row">
                        <div class="col-5 pr-1">
                            Nama Produk
                        </div>
                        <div class="col-2 px-1">
                            Jumlah
                        </div>
                        <div class="col-2 px-1">
                            Harga
                        </div>
                        <div class="col-3 pl-1">
                            Total
                        </div>
                    </div>
                </div>
                @foreach (json_decode($row->produk) as $list)
                <div class="border-bottom p-1">
                    <div class="row">
                        <div class="col-5 pr-1">
                            {{ Produk::find($list->produk)->nama }}
                        </div>
                        <div class="col-2 px-1">
                            {{ number_format($list->jumlah) }}
                        </div>
                        <div class="col-2 px-1 text-right">
                            {{ number_format($list->harga) }}
                        </div>
                        <div class="col-3 pl-1 text-right">
                            {{ number_format($list->harga * $list->jumlah) }}
                        </div>
                    </div>
                </div>
                @endforeach
                <div class="border-bottom border-top p-1">
                    <div class="row">
                        <div class="col-9 pr-1">
                            Sub Harga
                        </div>
                        <div class="col-3 pl-1 text-right">
                            {{ number_format($row->total_harga) }}
                        </div>
                    </div>
                </div>
                <div class="border-bottom p-1">
                    <div class="row">
                        <div class="col-9 pr-1">
                            Down Payment
                        </div>
                        <div class="col-3 pl-1 text-right">
                            {{ number_format($row->down_payment) }}
                        </div>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-md-4">
                        <div class="form-group">
                            <div class="small">PPN (%)</div>
                            Rp {{ number_format($row->tax ? 0 : $row->total_harga * (10/100)) }}
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <div class="small">Total Harga + PPN</div>
                            {{ number_format($row->total_harga + ($row->tax ? 0 : $row->total_harga
                            * (10/100))) }}
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <div class="small">Grand Total</div>
                            {{ number_format($row->total_harga + ($row->tax ? 0 : $row->total_harga
                            * (10/100)) - $row->down_payment) }}
                        </div>
                    </div>
                </div>

                @if ($row->keterangan)
                <div class="form-group">
                    <div class="small">Keterangan</div>
                    {{ $row->keterangan }}
                </div>
                @endif

                <div class="border-top border-bottom mt-2 py-1">
                    <b>Data Delivery</b>
                </div>

                @foreach ($row->antar as $i => $row)
                <div class="border-bottom">
                    <div class="row">
                        <div class="col pr-1">
                            {{ $row->purchasing->nomor_purchasing }}<br>
                            {{ $row->produk->nama }}
                        </div>
                        <div class="col-auto pl-1 text-right">
                            {{ Tanggal::date($row->tanggal) }}<br>
                            {{ number_format($row->qty) }} {{ $row->produk->tipesatuan->nama }}
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endforeach
</div>

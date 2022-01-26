@foreach ($data as $row)
<div class="border rounded p-2 mb-2">
    <div class="row">
        <div class="col pr-1">
            <div class="mb-1">
                <small>Nomor Transaksi</small><br>
                {{ $row->nomor_transaksi }}
            </div>
            <div class="">
                <small>Jumlah Ayam</small><br>
                {{ number_format(Header::jml_trans_head($row->id)) }} Ekor
            </div>
        </div>
        <div class="col-auto pl-1 text-right">
            <div class="mb-1">
                <small>Total Transaksi</small><br>
                {{ number_format($row->total_trans) }}
            </div>

            <a href="{{ route('penjualan.invoice', $row->id) }}" class="text-primary">
                <i class="fa fa-file-pdf-o"></i> Invoice
            </a>

            @if ($row->status == 1) |
            <span class="text-danger cursor hapus_trans" data-id="{{ $row->id }}">
                <i class="fa fa-trash"></i> Hapus
            </span>
            @endif

            @if ($row->status == 2) |
                @if ($row->adj)
                <span class="text-success">
                    Perubahan Ke Transaksi<br>{{ $row->perubahan_transaksi }}
                </span>
                @else
                <span class="text-info"><b>INFO!!</b><br>Lakukan Perubahan Transaksi</span>
                @endif
            @endif
        </div>
    </div>
</div>
@endforeach

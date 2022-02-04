@php
$total = 0 ;
$ekor = 0 ;
@endphp
@foreach ($data as $row)
<div class="border rounded p-2 mb-2">
    <div class="row">
        <div class="col pr-1">
            <small>Kandang</small><br>
            @php
            $total += $row->total_harga ;
            $ekor += $row->qty ;
            $exp = json_decode($row->riwayat->farm->json_data);
            @endphp
            <b class="text-info">Angkatan {{ $row->riwayat->angkatan }} | {{ $row->riwayat->farm->nama }}</b> | Bangunan
            {{ $exp->bangunan ?? '' }} | Kode {{ $exp->kode ?? '' }}<br>
            {{ number_format($row->qty) }} Ekor
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

<div class="row">
    <div class="col pr-1">
        Total Ayam<br><b>{{ number_format($ekor) }} Ekor</b>
    </div>
    <div class="col pl-1 text-right">
        Total Transaksi<br><b>Rp {{ number_format($total) }}</b>
    </div>
</div>

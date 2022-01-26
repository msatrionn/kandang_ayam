@foreach ($data as $row)
<div class="border rounded mb-2 p-2">
    <div class="row">
        <div class="col pr-1"><b class="text-primary">[{{ $row->tipeset->nama }}]</b> {{ $row->nama }}</div>
        <div class="col-auto pl-1">{{ number_format($row->jumlah_stock) }} {{ $row->tipesatuan->nama }}</div>
    </div>
</div>
@endforeach

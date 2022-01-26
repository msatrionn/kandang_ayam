@foreach ($data as $val)

<div class="border rounded cursor data-search mb-2 py-1 px-2">
    <div class="row">
        <div class="col pr-1">{{ $val->setup->nama }}</div>
        <div class="col-auto pl-1 text-right">{{ Tanggal::date($val->tanggal) }}</div>
    </div>

    <div class="row">
        <div class="col pr-1"></div>
        <div class="col-auto pl-1 text-right">Rp {{ number_format($val->nominal) }}</div>
    </div>
</div>
@endforeach

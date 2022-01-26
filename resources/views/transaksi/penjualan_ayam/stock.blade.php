@foreach ($data as $row)
@php
    $exp    =   json_decode($row->farm->json_data) ;
    $now    =   Carbon\Carbon::now(); // Tanggal sekarang
    $b_day  =   Carbon\Carbon::parse($row->tanggal); // Tanggal Lahir
    $age    =   $b_day->diffInDays($now);  // Menghitung umur
    $minggu =   $b_day->diffInWeeks($now);  // Menghitung umur

    $trans  =   ListTrans::where('stok_id', $row->id)
                ->where('type', 'jual_ayam')
                ->where('header_id', NULL)
                ->sum('qty') ;
@endphp
<a class="btn btn-outline-info btn-sm btn-block mb-2 collapsed" data-toggle="collapse" href="#collapse{{ $row->id }}" role="button" aria-expanded="false" aria-controls="collapse{{ $row->id }}">
    <b>Angkatan {{ $row->angkatan }} | {{ $row->farm->nama }}</b> | Bangunan {{ $exp->bangunan ?? '' }} | Kode {{ $exp->kode ?? '' }}
</a>
<div class="collapse" id="collapse{{ $row->id }}" style="">
    <div class="p-1 mb-4">
        <div class="row">
            <div class="col pr-1">
                Jumlah Ayam<br>{{ number_format($row->populasi_akhir - $trans) }} Ekor
            </div>
            <div class="col pl-1">
                Umur Ayam<br>{{ $age }} Hari ({{ $minggu }} Minggu)
            </div>
        </div>

        <div class="row mt-2">
            <div class="col pr-1">
                <div class="form-group">
                    Jumlah Jual Ayam
                    <input type="number" id="jual_ayam{{ $row->id }}" class="form-control">
                </div>
            </div>
            <div class="col pl-1">
                <div class="form-group">
                    Total Harga Jual
                    <input type="number" id="harga_ayam{{ $row->id }}" class="form-control" data-politespace data-politespace-grouplength="3" data-politespace-delimiter="," data-politespace-decimal-mark="." data-politespace-reverse>
                </div>
            </div>
        </div>

        <button class="btn btn-block btn-primary input_jual" data-id="{{ $row->id }}">Submit</button>
    </div>
</div>
@endforeach


<script>
jQuery( function(){
    jQuery( document ).trigger( "enhance" );
});
</script>

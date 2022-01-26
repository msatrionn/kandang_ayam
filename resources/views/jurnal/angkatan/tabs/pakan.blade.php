<div class="row">
    <div class="col-md-6 pr-md-1">
        <div class="form-group">
            @php
            $exp = json_decode($kandang->farm->json_data);
            @endphp
            Kandang : {{ $kandang->farm->nama }}<br>
            Doc In : {{ Tanggal::date($kandang->tanggal) }}
        </div>
    </div>

    <div class="col-md-6 pl-md-1">
        <div class="form-group">
            Hari Ke
            <input type="number" name="hari_pakan" id="hari_pakan" onkeyup="hari_pakan()" class="form-control">
        </div>
    </div>
</div>

<div id="view_pakan"></div>

<div class="mb-3 border rounded p-2">
    <div class="row">
        <div class="col pr-1">
            <div class="small bg-light p-1 text-center">Total Masuk</div>
            <input type="text" value="{{ $hitung_pakan['masuk'] ?? 0 }}" disabled
                class="form-control text-center rounded-0">
        </div>
        <div class="col pl-1">
            <div class="small bg-light p-1 text-center">Total Keluar</div>
            <input type="text" value="{{ $hitung_pakan['keluar'] ?? 0 }}" disabled
                class="form-control text-center rounded-0">
        </div>
    </div>
</div>

@if (COUNT($riwayat_pakan))
@foreach ($riwayat_pakan as $item)
<div class="border rounded p-2 mb-2">
    <div style="position: relative">
        <div style="position: absolute; z-index:9999; top: 0; right: 0">
            <i class="fa fa-trash text-danger hapus_pakan" data-id="{{ $item->id }}"></i>
        </div>
    </div>
    <div class="row">
        <div class="mb-1 col-md-1 col-3 pr-1">
            <div class="small">Hari</div>
            {{ $item->hari }}
        </div>
        <div class="mb-1 col-md-2 col-9 px-md-1 pr-1">
            <div class="small">Tanggal</div>
            {{ Tanggal::date($item->tanggal_kartu) }}
        </div>
        <div class="mb-1 col-md-3 col-12 px-md-1">
            <div class="small">Jenis</div>
            @if (!empty($item->pakan->nama))
            {{ $item->pakan->nama }}
            @endif
        </div>
        <div class="mb-1 text-center col px-md-1 pr-1">
            <div class="border-top d-block d-md-none"></div>
            <div class="small">Masuk</div>
            {{ $item->masuk }}
            <div class="border-bottom d-block d-md-none"></div>
        </div>
        <div class="mb-1 text-center col px-1">
            <div class="border-top d-block d-md-none"></div>
            <div class="small">Keluar</div>
            {{ $item->keluar }}
            <div class="border-bottom d-block d-md-none"></div>
        </div>
        <div class="mb-1 col-md-3 col-12 px-md-1">
            <div class="small">Penerima</div>
            {{ $item->penerima ? $item->user->name : "###" }}
        </div>
    </div>
    @if ($item->keterangan)
    <div class="small">Keterangan</div>
    {{ $item->keterangan }}
    @endif
</div>
@endforeach
@endif

<script>
    function hari_pakan() {
    $(document).ready(function() {
        var hari    =   $("#hari_pakan").val();
        var row_id  =   $("#angkatan").val();
        var kandang =   $("#pilih_kandang").val();

        if (hari > 0) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                url: "{{ route('angkatanayam.index', ['key' => 'hari_pakan']) }}",
                method: "GET",
                data: {
                    row_id  : row_id,
                    kandang : kandang,
                    hari    : hari,
                },
                success: function(data) {
                    $("#view_pakan").empty().append(data);
                }
            });
        } else {
            $("#view_pakan").empty();
        }

    });
}
</script>

<div class="row">
    <div class="col-md-6 pr-md-1">
        <div class="form-group">
            @php
                $exp    =   json_decode($kandang->farm->json_data);
            @endphp
            Kandang : {{ $kandang->farm->nama }}<br>
            Doc In : {{ Tanggal::date($kandang->tanggal) }}
        </div>
    </div>

    <div class="col-md-6 pl-md-1">
        <div class="form-group">
            Hari Ke
            <input type="number" name="hari_catatan" id="hari_catatan" onkeyup="hari_catatan()" class="form-control">
        </div>
    </div>
</div>

<div id="view_catatan"></div>

<script>
function hari_catatan() {
    $(document).ready(function() {
        var hari    =   $("#hari_catatan").val();
        var row_id  =   $("#angkatan").val();
        var kandang =   $("#pilih_kandang").val();

        if (hari > 0) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                url: "{{ route('angkatanayam.index', ['key' => 'hari_catatan']) }}",
                method: "GET",
                data: {
                    row_id: row_id,
                    kandang: kandang,
                    hari: hari,
                },
                success: function(data) {
                    $("#view_catatan").empty().append(data);
                }
            });
        } else {
            $("#view_catatan").empty();
        }

    });
}
</script>

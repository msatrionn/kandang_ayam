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
            <input type="number" name="hari_populasi" id="hari_populasi" onkeyup="hari_populasi()" class="form-control">
        </div>
    </div>
</div>

<div id="view_populasi"></div>

<script>
    function hari_populasi() {
        $(document).ready(function() {
            var hari    =   $("#hari_populasi").val();
            var row_id  =   $("#angkatan").val();
        var kandang =   $("#pilih_kandang").val();

            if (hari > 0) {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    url: "{{ route('angkatanayam.index', ['key' => 'hari_populasi']) }}",
                    method: "GET",
                    data: {
                        row_id  : row_id,
                        kandang : kandang,
                        hari    : hari,
                    },
                    success: function(data) {
                        $("#view_populasi").empty().append(data);
                    }
                });
            } else {
                $("#view_populasi").empty();
            }

        });
    }
</script>

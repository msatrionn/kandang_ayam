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
            <input type="number" name="hari_timbang" id="hari_timbang" onkeyup="hari_timbang()" class="form-control">
        </div>
    </div>
</div>

<div id="view_timbang"></div>

<script>
    function hari_timbang() {
        $(document).ready(function() {
            var hari    =   $("#hari_timbang").val();
            var row_id  =   $("#angkatan").val();
            var kandang =   $("#pilih_kandang").val();

            if (hari > 0) {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    url: "{{ route('angkatanayam.index', ['key' => 'hari_timbang']) }}",
                    method: "GET",
                    data: {
                        row_id  : row_id,
                        kandang : kandang,
                        hari    : hari,
                    },
                    success: function(data) {
                        $("#view_timbang").empty().append(data);
                    }
                });
            } else {
                $("#view_timbang").empty();
            }

        });
    }
</script>

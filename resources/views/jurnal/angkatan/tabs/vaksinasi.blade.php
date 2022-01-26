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
            <input type="number" name="umur_vaksinasi" id="umur_vaksinasi" onkeyup="umur_vaksin()" class="form-control">
        </div>
    </div>
</div>

<div id="view_vaksinasi"></div>

<div class="border-top mt-2 pt-2">
    @foreach ($riwayat_vaksin as $row)
    <div class="border rounded mb-2 p-2">
        <div style="position: relative">
            <div style="position: absolute; z-index:9999; top: 0; right: 0">
                <i class="fa fa-trash text-danger hapus_vaksinasi" data-id="{{ $row->id }}"></i>
            </div>
        </div>
        <div class="row">
            <div class="col-md-1 col-3 pr-1">
                <div class="small">Umur</div>
                {{ $row->umur }}
            </div>
            <div class="col-md-2 col-9 px-md-1 pl-1">
                <div class="small">Tanggal</div>
                {{ Tanggal::date($row->tanggal) }}
            </div>
            <div class="col-md-3 col-12 pt-1 pt-md-0 px-md-1">
                <div class="small">Vaksin</div>
                @if (!empty($row->stok->nama))
                {{ $row->stok->nama }}
                @endif
            </div>
            <div class="col-md-3 col-6 pt-1 pt-md-0 px-md-1 pr-1">
                <div class="small">Aplikasi</div>
                {{ $row->aplikasi }}
            </div>
            <div class="col-md-3 col-6 pt-1 pt-md-0 pl-1">
                <div class="small">Realisasi</div>
                {{ $row->realisasi ?? '###' }}
            </div>
        </div>
    </div>
    @endforeach
</div>

<script>
    var umur = $("#umur_vaksinasi").val();
    console.log(umur);
    $(function () {
        $('select').each(function () {
            $(this).select2({
            theme: 'bootstrap4',
            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
            placeholder: $(this).data('placeholder'),
            allowClear: Boolean($(this).data('allow-clear')),
            closeOnSelect: !$(this).attr('multiple'),
            });
        });
    });

    function umur_vaksin() {
        $(document).ready(function() {
            var umur    =   $("#umur_vaksinasi").val();
            console.log(umur);
            var kandang =   $("#pilih_kandang").val();
            var row_id  =   $("#angkatan").val();

            if (umur > 0) {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    url: "{{ route('angkatanayam.index', ['key' => 'umur_vaksinasi']) }}",
                    method: "GET",
                    data: {
                        row_id  : row_id,
                        kandang : kandang,
                        umur    : umur,
                    },
                    success: function(data) {
                        $("#view_vaksinasi").empty().append(data);
                    }
                });
            } else {
                $("#view_vaksinasi").empty();
            }

        });
    }
</script>

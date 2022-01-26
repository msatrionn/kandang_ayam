<div class="form-group">
    Kandang
    <select name="pilih_kandang" id="pilih_kandang" onchange="kandang()" data-width="100%" data-placeholder="Pilih Kandang" class="form-control select2">
        <option value=""></option>
        @foreach ($listriwayat as $row)
        <option value="{{ $row->id }}">{{ $row->farm->nama }}</option>
        @endforeach
    </select>
</div>

<div id="show_data"></div>

<script>
function kandang() {
    $(document).ready(function() {
        var kandang     =   $("#pilih_kandang").val();
        var angkatan    =   "{{ $angkatan }}";

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            url: "{{ route('angkatanayam.index', ['key' => 'show_data']) }}",
            method: "GET",
            data: {
                kandang     :   kandang,
                angkatan    :   angkatan,
            },
            success: function(data) {
                $("#show_data").empty().append(data);
            }
        });
    });
}
</script>

<script>
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
</script>

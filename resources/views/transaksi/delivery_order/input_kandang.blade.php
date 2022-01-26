<div id="pilihkandang">
    <div class="form-group"  class="kandang_in">
        Kandang
        <select name="pilih_kandang" id="pilih_kandang" data-width="100%" data-placeholder="Pilih Kandang" class="form-control select2">
            <option value=""></option>
            @foreach ($listriwayat as $row)
            <option value="{{ $row->id }}">{{ $row->farm->nama }}</option>
            @endforeach
        </select>
    </div>
</div>


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

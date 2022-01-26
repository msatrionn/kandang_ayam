<div class="card-body">
    <div class="form-group">
        Tanggal Mutasi
        <input type="date" id="tanggal_mutasi" class="form-control">
    </div>

    <div class="form-group">
        Dari Kas
        <select id="dari_kas" data-placeholder="Pilih Kas Dari" data-width="100%" class="form-control select2">
            <option value=""></option>
            @foreach ($payment as $id => $nama)
            <option value="{{ $id }}">{{ $nama }}</option>
            @endforeach
        </select>
    </div>

    <div class="form-group">
        Transfer Ke
        <select id="transfer_ke" data-placeholder="Pilih Kas Transfer" data-width="100%" class="form-control select2">
            <option value=""></option>
            @foreach ($payment as $id => $nama)
            <option value="{{ $id }}">{{ $nama }}</option>
            @endforeach
        </select>
    </div>

    <div class="form-group">
        Nominal Mutasi
        <input type="number" id="nominal_mutasi" class="form-control" placeholder="Tuliskan Nominal Setor Modal" autocomplete="off" data-politespace data-politespace-grouplength="3" data-politespace-delimiter="," data-politespace-decimal-mark="." step="0.01" data-politespace-reverse>
    </div>
</div>
<div class="card-footer text-right">
    <button type="button" id="btnInput" class="btn btn-primary">Submit</button>
</div>


<script>
jQuery( function(){
    jQuery( document ).trigger( "enhance" );
});
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

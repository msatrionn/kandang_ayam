<div class="row">
    <div class="col-8 pr-1">
        <div class="form-group">
            Pilih Stock Dimutasi
            <select name="produk" id="produk" class="form-control select2" data-placeholder="Pilih Stock">
                <option value=""></option>
                @foreach ($data as $item)
                <option value="{{ $item->id }}">{{ $item->nama }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-4 pl-1">
        <div class="form-group">
            Qty
            <input type="numeric" class="form-control" id="qty">
        </div>
    </div>
</div>

<div class="form-group">
    Tanggal Mutasi
    <input type="date" name="tanggal_mutasi" id="tanggal_mutasi" class="form-control">
</div>

<div class="form-group">
    Pilih Kandang
    <select name="kandang" id="kandang" data-placeholder="Pilih Kandang" data-width="100%" class="form-control select2">
        <option value=""></option>
        @foreach ($kandang as $row)
        <option value="{{ $row->id }}">{{ $row->nama }}</option>
        @endforeach
    </select>
</div>

<button class="btn btn-primary btn-block" id="mutasi_stock">Submit</button>

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

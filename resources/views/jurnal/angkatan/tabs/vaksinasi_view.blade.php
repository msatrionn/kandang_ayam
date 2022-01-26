<div class="row">
    <div class="col-md-6 pr-md-1">
        <div class="form-group">
            Tanggal Vaksinasi
            <input type="text" disabled id="tanggal_vaksinasi" value="{{ Tanggal::date(Carbon\Carbon::parse($data->tanggal)->addDays(($umur - 1))) }}" class="form-control">
        </div>

        <div class="form-group">
            Vaksin
            <select name="vaksin_vaksinasi" id="vaksin_vaksinasi" data-placeholder="Pilih Vaksin" class="form-control">
                <option value=""></option>
                @foreach ($vaksin as $row)
                <option value="{{ $row->produk_id }}">{{ $row->produk->nama }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="col-md-6 pl-md-1">

        <div class="form-group">
            Aplikasi
            <input type="text" name="aplikasi_vaksinasi" id="aplikasi_vaksinasi" class="form-control">
        </div>

        <div class="form-group">
            Realisasi
            <input type="text" name="realisasi_vaksinasi" id="realisasi_vaksinasi" class="form-control">
        </div>

    </div>
</div>

<div class="form-group">
    <button id="input_vaksinasi" class="btn btn-block btn-primary">Submit</button>
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

<div class="row">
    <div class="col-md-6 pr-md-1">
        <div class="form-group">
            Pakan Tanggal
            <input type="text" value="{{ Tanggal::date(Carbon\Carbon::parse($data->tanggal)->addDays(($hari - 1))) }}"
                disabled class="form-control">
        </div>

        <div class="form-group">
            Jenis Pakan
            <select name="jenis_pakan" id="jenis_pakan" data-placeholder="Pilih Jenis Pakan" class="form-control">
                <option value=""></option>
                @foreach ($pakan as $row)
                <option value="{{ $row->produk_id }}">{{ $row->produk->nama }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="col-md-6 pl-md-1">
        <div class="form-group">
            Penerima
            <div id="penerima_selected">
                <select name="pilih Penerima" data-placeholder="Pilih Penerima" class="form-control"
                    id="pilih_penerima">
                    <option value=""></option>
                    @foreach ($penerima as $item)
                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                    @endforeach
                </select>
            </div>
            <input type="text" name="input_penerima" id="input_penerima" placeholder="Tulis Nama Penerima"
                style="display: none" class="form-control">
            <label class="mt-2"><input id="check_penerima" type="checkbox"> Input penerima manual / Tidak ada di
                list</label>
        </div>
    </div>
</div>

<div class="text-center font-weight-bold pb-2 mb-2 border-bottom">Jumlah Pakan</div>
<div class="row">
    <div class="col-6 pr-1">
        <div class="form-group">
            <div class="small bg-light p-1 text-center">Masuk</div>
            <input type="number" name="pakan_masuk" id="pakan_masuk" class="form-control rounded-0">
        </div>
    </div>
    <div class="col-6 pl-1">
        <div class="form-group">
            <div class="small bg-light p-1 text-center">Keluar</div>
            <input type="hidden" name="pakan_keluar_awal" id="pakan_keluar_awal" class="form-control rounded-0">
            <input type="number" name="pakan_keluar" id="pakan_keluar" class="form-control rounded-0">
        </div>
    </div>
</div>

<div class="form-group">
    Keterangan
    <textarea name="keterangan_pakan" id="keterangan_pakan" rows="3" class="form-control"></textarea>
</div>

<div class="form-group">
    <button type="button" id="input_pakan" class="btn btn-block btn-primary">Submit</button>
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

    $(document).ready(function() {
        $(document).on('click', '#check_penerima', function() {
            var input       =   $("#check_penerima") ;
            var selected    =   document.getElementById('penerima_selected');
            var select      =   document.getElementById('pilih_penerima');
            var text        =   document.getElementById('input_penerima');

            if (input.prop('checked')){
                selected.style  =   'display: none' ;
                select.style    =   'display: none' ;
                text.style      =   'display: block' ;
                text.value      =   '';
                $("#penerima_select").val("").trigger("change");
            } else {
                selected.style  =   'display: block' ;
                select.style    =   'display: block' ;
                text.style      =   'display: none' ;
                text.value      =   '';
                $("#penerima_select").val("").trigger("change");
            }
        });
    });
</script>

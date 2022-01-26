<div class="row">
    <div class="col-md-6 pr-md-1">
        <div class="form-group">
            OVK Tanggal
            <input type="text" value="{{ Tanggal::date(Carbon\Carbon::parse($data->tanggal)->addDays(($hari - 1))) }}" disabled class="form-control">
        </div>

        <div class="form-group">
            Jenis OVK
            <select name="jenis_ovk" id="jenis_ovk" data-placeholder="Pilih Jenis OVK" class="form-control">
                <option value=""></option>
                @foreach ($ovk as $row)
                <option value="{{ $row->produk_id }}">{{ $row->produk->nama }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="col-md-6 pl-md-1">
        <div class="form-group">
            Penerima
            <div id="penerima_selected_ovk">
                <select name="pilih Penerima" data-placeholder="Pilih Penerima" class="form-control" id="pilih_penerima_ovk">
                    <option value=""></option>
                    @foreach ($penerima as $item)
                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                    @endforeach
                </select>
            </div>
            <input type="text" name="input_penerima" id="input_penerima_ovk" placeholder="Tulis Nama Penerima" style="display: none" class="form-control">
            <label class="mt-2"><input id="check_penerima_ovk" type="checkbox"> Input penerima manual / Tidak ada di list</label>
        </div>
    </div>
</div>

<div class="text-center font-weight-bold pb-2 mb-2 border-bottom">Jumlah OVK</div>
<div class="row">
    <div class="col pr-1">
        <div class="form-group">
            <div class="small bg-light p-1 text-center">Masuk</div>
            <input type="number" name="ovk_masuk" id="ovk_masuk" class="form-control rounded-0">
        </div>
    </div>
    <div class="col pl-1">
        <div class="form-group">
            <div class="small bg-light p-1 text-center">Keluar</div>
            <input type="number" name="ovk_keluar" id="ovk_keluar" class="form-control rounded-0">
        </div>
    </div>
</div>

<div class="form-group">
    Keterangan
    <textarea name="keterangan_ovk" id="keterangan_ovk" rows="3" class="form-control"></textarea>
</div>

<div class="form-group">
    <button type="button" id="input_ovk" class="btn btn-block btn-primary">Submit</button>
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
        $(document).on('click', '#check_penerima_ovk', function() {
            var input       =   $("#check_penerima_ovk") ;
            var selected    =   document.getElementById('penerima_selected_ovk');
            var select      =   document.getElementById('pilih_penerima_ovk');
            var text        =   document.getElementById('input_penerima_ovk');

            if (input.prop('checked')){
                selected.style  =   'display: none' ;
                text.style      =   'display: block' ;
                text.value      =   '';
                $("#pilih_penerima_ovk").val("").trigger("change");
            } else {
                selected.style  =   'display: block' ;
                text.style      =   'display: none' ;
                text.value      =   '';
                $("#pilih_penerima_ovk").val("").trigger("change");
            }
        });
    });
</script>

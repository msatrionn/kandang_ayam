<div class="form-group">
    Tanggal Pembayaran
    <input type="date" name="tanggal_pembayaran" class="form-control" id="tanggal_pembayaran" autocomplete="off">
</div>

<div class="form-group">
    Nominal Dibayarkan
    <input type="numeric" class="form-control" id="nominal_dibayarkan" name="nominal_dibayarkan" data-politespace data-politespace-grouplength="3" data-politespace-delimiter="," data-politespace-decimal-mark="." step="0.01" data-politespace-reverse>
</div>

<div class="form-group">
    Metode Pembayaran
    <div id="metode_select">
        <select name="metode_pembayaran" id='metode_pembayaran' data-placeholder="Pilih Metode Pembayaran" class="form-control">
            <option value=""></option>
            @foreach ($pay as $id => $row)
            <option value="{{ $id }}">{{ $row }}</option>
            @endforeach
        </select>
    </div>
    <input type="text" style="display: none" name="tulis_pembayaran" id="tulis_pembayaran" placeholder="Tulis Pembayaran" autocomplete="off" class="form-control">
    <label class="mt-2"><input id="check_kas" name="check_kas" type="checkbox"> Input metode pembayaran manual / Tidak ada di list</label>
    <div id="errPayment"></div>
</div>

<div class="form-group text-right">
    <button type="button" class="input_pengeluaran btn btn-primary">Submit</button>
</div>

<script src="{{ asset('js/vendor/politespace/libs/libs.js') }}"></script>
<script src="{{ asset('js/vendor/politespace/politespace.js') }}"></script>
<script src="{{ asset('js/vendor/politespace/politespace-init.js') }}"></script>

<script src="{{ asset('assets/vendor/select2/select2.js') }}"></script>
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

<script>
    $(document).ready(function() {
        $(document).on('click', '#check_kas', function() {
            var metode_select       =   $("#check_kas") ;
            var input               =   document.getElementById('metode_select');
            var metode              =   document.getElementById('metode_pembayaran');
            var tulis_kas           =   document.getElementById('tulis_pembayaran');

            if (metode_select.prop('checked')){
                input.style         =   'display: none' ;
                tulis_kas.style     =   'display: block' ;
                tulis_kas.value     =   '';
                $("#metode_pembayaran").val("").trigger("change");
            } else {
                input.style         =   'display: block' ;
                tulis_kas.style     =   'display: none' ;
                tulis_kas.value     =   '';
                $("#metode_pembayaran").val("").trigger("change");
            }
        });
    });
</script>

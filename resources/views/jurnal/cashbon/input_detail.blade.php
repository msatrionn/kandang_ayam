<div class="form-group">
    Tanggal Cashbon
    <input type="date" id="tanggal" class="form-control">
</div>

<div class="form-group">
    Nominal
    <input type="number" min="1" id="nominal" class="form-control" placeholder="Nominal Potongan Gaji" data-politespace data-politespace-grouplength="3" data-politespace-delimiter="," data-politespace-decimal-mark="." data-politespace-reverse autocomplete="off">
</div>

<div class="form-group">
    Metode Pembayaran
    <div id="metode_select">
        <select id='metode_pembayaran' data-placeholder="Pilih Metode Pembayaran" class="form-control">
            <option value=""></option>
            @foreach ($payment as $id => $row)
            <option value="{{ $id }}">{{ $row }}</option>
            @endforeach
        </select>
    </div>
    <input type="text" style="display: none" name="tulis_pembayaran" id="tulis_pembayaran" placeholder="Tulis Pembayaran" autocomplete="off" class="form-control">
    <label class="mt-2"><input id="check_kas" name="check_kas" type="checkbox"> Input metode pembayaran manual / Tidak ada di list</label>
</div>

<div class="form-group">
    <button type="button" class="btn btn-primary btn-block" id="proses_cashbon">Submit</button>
</div>


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

<div class="form-group">
    Perubahan Transaksi
    <select id='perubahan' data-placeholder="Pilih Nomor Transaksi (Jika Melakukan Perubahan)" class="form-control">
        <option value=""></option>
        @foreach ($transaksi as $row)
        <option value="{{ $row->id }}">{{ $row->nomor_transaksi }}</option>
        @endforeach
    </select>
</div>

<div class="row">
    <div class="col-md-6 pr-md-1">
        <label class="mt-2"><input id="check_konsumen" type="checkbox"> Input konsumen manual / Tidak ada di list</label>

        <div class="form-group" id="select_konsumen">
            Konsumen
            <select id="konsumen" data-placeholder="Pilih Konsumen" data-width="100%">
                <option value=""></option>
                @foreach ($konsumen as $id => $row)
                <option value="{{ $id }}">{{ $row }}</option>
                @endforeach
            </select>
        </div>
        <div id="input_konsumen" style="display: none">
            <div class="form-group">
                Nama Konsumen
                <input type="text" name="nama_konsumen" class="form-control" id="nama_konsumen" placeholder="Tuliskan Nama Konsumen" autocomplete="off">
            </div>

            <div class="form-group">
                Nomor Telepon
                <input type="number" name="nomor_telepon" class="form-control" id="nomor_telepon" placeholder="Tuliskan Nomor Telepon" autocomplete="off">
            </div>

            <div class="form-group">
                Alamat
                <textarea name="alamat" class="form-control" id="alamat" rows="3"></textarea>
            </div>
        </div>
    </div>
    <div class="col-md-6 pl-md-1">
        <div class="mb-3">&nbsp;</div>
        <div class="form-group">
            Tanggal Transaksi
            <input type="date" class="form-control" id="tanggal">
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6 pr-md-1">
        <div class="form-group">
            Nominal Dibayarkan
            <input type="number" id="nominal_bayar" class="form-control" data-politespace data-politespace-grouplength="3" data-politespace-delimiter="," data-politespace-decimal-mark="." data-politespace-reverse>
        </div>
    </div>
    <div class="col-md-6 pl-md-1">
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
    </div>
</div>

<div class="form-group text-right">
    <button class="btn btn-primary" id="selesaikan">Selesaikan</button>
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

jQuery( function(){
    jQuery( document ).trigger( "enhance" );
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


    $(document).ready(function() {
        $(document).on('click', '#check_konsumen', function() {
            var checked =   $("#check_konsumen") ;
            var select  =   document.getElementById('select_konsumen');
            var input   =   document.getElementById('input_konsumen');

            if (checked.prop('checked')){
                select.style    =   'display: none' ;
                input.style     =   'display: block' ;
            } else {
                select.style    =   'display: block' ;
                input.style     =   'display: none' ;
            }
        });
    });
</script>

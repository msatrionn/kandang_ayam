<div class="form-group">
    Angkatan
    <select name="angkatan" id="angkatan" class="form-control select2" data-width="100%"
        data-placeholder="Pilih angkatan">
        <option value=""></option>
        <option value="ALL">Tanpa Angkatan </option>
        @foreach ($angkatan as $id => $row)
        <option value="{{ $row }}">{{ $row }}</option>
        @endforeach
    </select>
</div>
<div id="kandang-select">
    <div class="form-group">
        Kandang
        <select name="kandang" id="kandang" class="form-control select2" data-width="100%"
            data-placeholder="Pilih Kandang">
            <option value=""></option>
            <option value="ALL">Tanpa Kandang </option>
            @foreach ($kandang as $id => $row)
            <option value="{{ $id }}">{{ $row }}</option>
            @endforeach
        </select>
    </div>
</div>

<div class="form-group">
    <a href="{{ route('pembelian.setup') }}" class="float-right">Setup Produk</a>
    Produk
    <div id="selected">
        <select name="produk" id="produk" data-placeholder="Pilih Produk" data-width="100%"
            class="form-control select2">
            <option value=""></option>
            @foreach ($produk as $row)
            <option value="{{ $row->id }}">{{ $row->nama }}</option>
            @endforeach
        </select>
    </div>

    <div class="form-group mt-3">
        Jenis
        <div id="selected_jenis">
            <select name="jenis" id="jenis" data-placeholder="Pilih Jenis" data-width="100%"
                class="form-control select2">
                <option value="">Pilih Jenis</option>
                <option value="pemanas">Pemanas</option>
            </select>
        </div>
    </div>

    <div id="write_produk" style="display: none">
        <div class="form-group">
            <input type="text" name="tulis_produk" id="tulis_produk" placeholder="Tulis Produk" autocomplete="off"
                class="form-control">
        </div>

        <div class="form-group mb-0">
            Satuan
            <div id="satuan_select">
                <select name="satuan" class="form-control" id="satuan" data-placeholder="Pilih Satuan">
                    <option value=""></option>
                    @foreach ($satuan as $id => $nama)
                    <option value="{{ $id }}">{{ $nama }}</option>
                    @endforeach
                </select>
            </div>
            <input type="text" style="display: none" name="tulis_satuan" id="tulis_satuan" placeholder="Tulis Satuan"
                autocomplete="off" class="form-control" autocomplete="off">
            <label class="mt-2"><input id="check_satuan" name="check_satuan" type="checkbox"> Input satuan manual /
                Tidak ada di list</label>
        </div>
    </div>
    <label class="mt-2"><input id="check_produk" name="check_produk" type="checkbox"> Input produk manual / Tidak ada di
        list</label>
</div>

<div class="row">
    <div class="col-6 pr-1">
        <div class="form-group">
            Jumlah Pembelian
            <input type="number" name="jumlah_beli" onkeyup="hitung()" id="jumlah_beli" class="form-control">
        </div>
    </div>
    <div class="col-6 pl-1">
        <div class="form-group">
            Harga Satuan
            <input type="number" onkeyup="hitung()" id="harga_pembelian" class="form-control" data-politespace
                data-politespace-grouplength="3" data-politespace-delimiter="," data-politespace-decimal-mark="."
                data-politespace-reverse>
        </div>
    </div>
</div>

<div class="form-group">
    Total Transaksi
    <div class="font-weight-bold" id="total_transaksi">Rp 0</div>
</div>

<div class="form-group">
    Tanggal
    <input type="date" id="tanggal" class="form-control">
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
    <input type="text" style="display: none" name="tulis_pembayaran" id="tulis_pembayaran"
        placeholder="Tulis Pembayaran" autocomplete="off" class="form-control">
    <label class="mt-2"><input id="check_kas" name="check_kas" type="checkbox"> Input metode pembayaran manual / Tidak
        ada di list</label>
</div>

<div class="form-group">
    <button class="btn btn-block btn-primary" id="input_pembelian">Submit</button>
</div>

<script>
    function hitung() {
    var jumlah  =   document.getElementById("jumlah_beli").value ;
    var harga   =   document.getElementById("harga_pembelian").value ;

    document.getElementById('total_transaksi').innerHTML    =   accounting.formatMoney(harga * jumlah, 'Rp ', 0);
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

jQuery( function(){
    jQuery( document ).trigger( "enhance" );
});
</script>

<script>
    $(document).ready(function() {
        $(document).on('click', '#check_produk', function() {
            var check   =   $("#check_produk") ;
            var select  =   document.getElementById('selected');
            var input   =   document.getElementById('write_produk');

            if (check.prop('checked')){
                select.style    =   'display: none' ;
                input.style     =   'display: block' ;
            } else {
                select.style    =   'display: block' ;
                input.style     =   'display: none' ;
            }
        });
    });
</script>

<script>
    $(document).ready(function() {
        $(document).on('click', '#check_satuan', function() {
            var input                   =   $("#check_satuan") ;
            var satuan_select           =   document.getElementById('satuan_select');
            var tulis_satuan             =   document.getElementById('tulis_satuan');

            if (input.prop('checked')){
                satuan_select.style     =   'display: none' ;
                tulis_satuan.style       =   'display: block' ;
                tulis_satuan.value       =   '';
                $("#satuan").val("").trigger("change");
            } else {
                satuan_select.style     =   'display: block' ;
                tulis_satuan.style       =   'display: none' ;
                tulis_satuan.value       =   '';
                $("#satuan").val("").trigger("change");
            }
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
<script>
    $("[name=angkatan]").on('change',function () {
            $.ajax({
                url:"{{ route('purchasing.index',['key'=>'kandang']) }}",
                method:"GET",
                data:{
                    angkatan_id:$(this).val()
                },
                success:function (data) {
                    $("#kandang-select").html(data)
                }
            })
        })
</script>

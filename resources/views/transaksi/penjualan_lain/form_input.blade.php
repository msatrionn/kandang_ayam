<div class="col-auto pl-1 text-right">
    <i class="fa fa-plus cursor text-success pt-2 mt-1 add_class"></i>
</div>
<div class="form-group">
    Kandang
    <select name="kandang[]" class="form-control select2 kandang" data-width="100%" data-placeholder="Pilih Kandang">
        <option value=""></option>
        <option value="ALL">Tanpa Kandang </option>
        @foreach ($kandang as $id => $row)
        <option value="{{ $id }}">{{ $row }}</option>
        @endforeach
    </select>
</div>

<div class="form-group">
    Produk
    <div id="selected">
        <select name="produk[]" data-placeholder="Pilih Produk" data-width="100%" class="form-control select2 produk">
            <option value=""></option>
            @foreach ($produk as $row)
            <option value="{{ $row->id }}">{{ $row->nama }}</option>
            @endforeach
        </select>
    </div>
    <div id="write_produk" style="display: none">
        <div class="form-group">
            <input type="text" name="tulis_produk[]" id="tulis_produk" placeholder="Tulis Produk" autocomplete="off"
                class="form-control tulis_produk">
        </div>

        <div class="form-group mb-0">
            Satuan
            <div id="satuan_select">
                <select name="satuan[]" class="form-control" id="satuan" data-placeholder="Pilih Satuan">
                    <option value=""></option>
                    @foreach ($satuan as $id => $nama)
                    <option value="{{ $id }}">{{ $nama }}</option>
                    @endforeach
                </select>
            </div>
            <input type="text" style="display: none" name="tulis_satuan[]" id="tulis_satuan" placeholder="Tulis Satuan"
                autocomplete="off" class="form-control tulis_satuan" autocomplete="off">
            <label class="mt-2"><input id="check_satuan" class="check_satuan" name="check_satuan[]" type="checkbox">
                Input satuan manual /
                Tidak ada di list</label>
        </div>
    </div>
    <label class="mt-2"><input id="check_produk" name="check_produk[]" class="check_produk" type="checkbox"> Input
        produk manual / Tidak ada
        di
        list</label>
</div>

<div class="row">
    <div class="col pr-1">
        <div class="form-group">
            Jumlah
            <input type="number" id="jumlah" name="jumlah" placeholder="Jumlah Penjualan" min="1" onkeyup="hitung()"
                class="form-control" autocomplete="off">
        </div>
    </div>

    <div class="col pl-1">
        <div class="form-group">
            Harga Satuan
            <input type="number" id="nominal" name='nominal' placeholder="Tuliskan Total Transaksi" min="1"
                onkeyup="hitung()" class="form-control" data-politespace data-politespace-grouplength="3"
                data-politespace-delimiter="," data-politespace-decimal-mark="." data-politespace-reverse
                autocomplete="off">
        </div>
    </div>
</div>


<div class="form-group">
    Total Transaksi
    <div class="font-weight-bold" id="total_transaksi">Rp 0</div>
</div>
<div id="add">
</div>

<div class="form-group">
    <button class="btn btn-block btn-primary" id="input_jual">Submit</button>
</div>

<script>
    function hitung() {
    var jumlah  =   document.getElementById("jumlah").value ;
    var nominal =   document.getElementById("nominal").value ;
    document.getElementById("total_transaksi").innerHTML    =   accounting.formatMoney(nominal * jumlah, 'Rp ', 0);
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
                $("[name=satuan]").val("").trigger("change");
            } else {
                satuan_select.style     =   'display: block' ;
                tulis_satuan.style       =   'display: none' ;
                tulis_satuan.value       =   '';
                $("[name=satuan]").val("").trigger("change");
            }
        });
    });
</script>
<script>
    var count=3;
    $('.add_class').on("click",function(){
        count++;
        console.log(count);
        var form=`<div class="add${count}">
            <div class="col-auto pl-1 text-right">
                <i class="fa fa-trash cursor text-danger pt-2 mt-1" onClick="removeRow(${count})"></i>
            </div>
            <div class="form-group">
                Kandang
                <select name="kandang[]" class="form-control select kandang" data-width="100%" data-placeholder="Pilih Kandang">
                    <option value=""></option>
                    <option value="ALL">Tanpa Kandang </option>
                    @foreach ($kandang as $id => $row)
                    <option value="{{ $id }}">{{ $row }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                Produk
                <div id="selected${count}">
                    <select name="produk[]" data-placeholder="Pilih Produk" data-width="100%"
                        class="form-control select produk">
                        <option value=""></option>
                        @foreach ($produk as $row)
                        <option value="{{ $row->id }}">{{ $row->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div id="write_produk${count}" style="display: none">
                    <div class="form-group">
                        <input type="text" name="tulis_produk[]" id="tulis_produk${count}" placeholder="Tulis Produk" autocomplete="off"
                            class="form-control tulis_produk">
                    </div>

                    <div class="form-group mb-0">
                        Satuan
                        <div id="satuan_select${count}">
                            <select name="satuan[]" class="form-control" data-placeholder="Pilih Satuan">
                                <option value=""></option>
                                @foreach ($satuan as $id => $nama)
                                <option value="{{ $id }}">{{ $nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <input type="text" style="display: none" name="tulis_satuan[]" id="tulis_satuan${count}"
                            placeholder="Tulis Satuan" autocomplete="off" class="form-control tulis_satuan" autocomplete="off">
                        <label class="mt-2"><input id="check_satuan${count}" class="check_satuan" name="check_satuan[]" type="checkbox"> Input satuan manual /
                            Tidak ada di list</label>
                    </div>
                </div>
                <label class="mt-2"><input id="check_produk${count}" class="check_produk" name="check_produk[]" type="checkbox"> Input produk manual / Tidak
                    ada di
                    list</label>
            </div>

            <div class="row">
                <div class="col pr-1">
                    <div class="form-group">
                        Jumlah
                        <input type="number" id="jumlah${count}" name="jumlah" placeholder="Jumlah Penjualan" min="1" onkeyup="hitung2(${count})"
                            class="form-control" autocomplete="off">
                    </div>
                </div>

                <div class="col pl-1">
                    <div class="form-group">
                        Harga Satuan
                        <input type="number" id="nominal${count}" name="nominal" placeholder="Tuliskan Total Transaksi" min="1" onkeyup="hitung2(${count})"
                            class="form-control" data-politespace data-politespace-grouplength="3"
                            data-politespace-delimiter="," data-politespace-decimal-mark="." data-politespace-reverse
                            autocomplete="off">
                    </div>
                </div>
            </div>
            <div class="form-group">
                Total Transaksi
                <div class="font-weight-bold" id="total_transaksi${count}">Rp 0</div>
            </div>
        </div>`
        $("#add").append(form)

        $('select').each(function () {
        $(this).select2({
        theme: 'bootstrap4',
        width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
        placeholder: $(this).data('placeholder'),
        allowClear: Boolean($(this).data('allow-clear')),
        closeOnSelect: !$(this).attr('multiple'),
        });
        });
        $(document).ready(function() {
        $(document).on('click', `#check_produk${count}`, function() {
        var check = $(`#check_produk${count}`) ;
        var select = document.getElementById(`selected${count}`);
        var input = document.getElementById(`write_produk${count}`);

        if (check.prop('checked')){
        select.style = 'display: none' ;
        input.style = 'display: block' ;
        } else {
        select.style = 'display: block' ;
        input.style = 'display: none' ;
        }
        });
        });
        $(document).ready(function() {
        $(document).on('click', `#check_satuan${count}`, function() {
        var input = $(`#check_satuan${count}`) ;
        var satuan_select = document.getElementById(`satuan_select${count}`);
        var tulis_satuan = document.getElementById(`tulis_satuan${count}`);

        if (input.prop('checked')){
        satuan_select.style = 'display: none' ;
        tulis_satuan.style = 'display: block' ;
        tulis_satuan.value = '';
        $("[name=satuan]").val("").trigger("change");
        } else {
        satuan_select.style = 'display: block' ;
        tulis_satuan.style = 'display: none' ;
        tulis_satuan.value = '';
        $("[name=satuan]").val("").trigger("change");
        }
        });
        });

})
function hitung2(count) {
    console.log(count);
var jumlah = document.getElementById(`jumlah${count}`).value ;
var nominal = document.getElementById(`nominal${count}`).value ;
console.log(jumlah+nominal);
document.getElementById(`total_transaksi${count}`).innerHTML = accounting.formatMoney(nominal * jumlah, 'Rp ', 0);
}

</script>
<script>
    function removeRow(count_id) {
    $(`.add${count_id}`).remove()
    }
</script>

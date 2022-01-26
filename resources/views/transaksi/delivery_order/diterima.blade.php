<div class="row">
    <div class="col-6 pr-1">
        <div class="form-group">
            Tanggal Kirim
            <input type="date" name="tanggal_kirim" class="form-control" id="tanggal_kirim" autocomplete="off">
        </div>
    </div>
    <div class="col-6 pl-1">
        <div class="form-group">
            Jumlah Pengiriman
            <input type="number" name="jumlah_pengiriman" class="form-control" id="jumlah_pengiriman"
                placeholder="Jumlah Pengiriman" autocomplete="off" data-politespace data-politespace-grouplength="3"
                data-politespace-delimiter="," data-politespace-decimal-mark="." step="0.01" data-politespace-reverse>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-6 pr-1">
        <div class="form-group">
            Biaya Pengiriman
            <input type="number" name="biaya_pengiriman" class="form-control" value="{{ old('biaya_pengiriman') }}"
                onkeyup="hitung(); return false;" id="biaya_pengiriman" placeholder="Nominal Biaya Pengiriman"
                autocomplete="off" data-politespace data-politespace-grouplength="3" data-politespace-delimiter=","
                data-politespace-decimal-mark="." step="0.01" data-politespace-reverse>
        </div>
    </div>
    <div class="col-6 pl-1">
        <div class="form-group">
            Beban Angkut
            <input type="number" name="beban_angkut" class="form-control" value="{{ old('beban_angkut') }}"
                onkeyup="hitung(); return false;" id="beban_angkut" placeholder="Nominal Beban Angkut"
                autocomplete="off" data-politespace data-politespace-grouplength="3" data-politespace-delimiter=","
                data-politespace-decimal-mark="." step="0.01" data-politespace-reverse>
        </div>
    </div>
</div>
<div class="form-group" id='angkatan' style="display: none">
    Pilih Angkatan
    <div id="angkatan_select">
        <select name="angkatan" data-placeholder="Pilih Angkatan" class="form-control" onchange="angkatan()">
            <option value="">pilih angkatan</option>
            @foreach ($data as $item)
            <option value="{{ $item->id }}">{{ $item->no }}</option>
            @endforeach
        </select>
    </div>

    <input type="text" style="display: none" name="tulis_angkatan" id="tulis_angkatan" placeholder="Tulis Angkatan"
        autocomplete="off" class="form-control">
    <label class="mt-2"><input id="check_angkatan" name="check_angkatan" type="checkbox"> Input Angkatan manual / Tidak
        ada di list</label>
    <div id="errPayment"></div>
</div>

<div id="kdg2"></div>

<div id="show_kandang"></div>

<div class="form-group">
    Biaya Lain-Lain
    <input type="number" name="biaya_lain_lain" class="form-control" value="{{ old('biaya_lain_lain') }}"
        onkeyup="hitung(); return false;" id="biaya_lain_lain" placeholder="Nominal Biaya Lainnya" autocomplete="off"
        data-politespace data-politespace-grouplength="3" data-politespace-delimiter=","
        data-politespace-decimal-mark="." step="0.01" data-politespace-reverse>
</div>

<div class="form-group" style="display: none" id='metode'>
    Metode Pembayaran
    <div id="metode_select">
        <select name="metode_pembayaran" id='metode_pembayaran' data-placeholder="Pilih Metode Pembayaran"
            class="form-control">
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
    <div id="errPayment"></div>
</div>

<button type="button" class="input_delivery btn btn-primary btn-block">Submit</button>

<script src="{{ asset('js/vendor/politespace/politespace.js') }}"></script>
<script src="{{ asset('js/vendor/politespace/politespace-init.js') }}"></script>
<script src="{{ asset('assets/vendor/select2/select2.js') }}"></script>

<script>
    function hitung() {
    var biaya_pengiriman    =   parseFloat(document.getElementById('biaya_pengiriman').value);
    var beban_angkut        =   parseFloat(document.getElementById('beban_angkut').value);
    var biaya_lain_lain     =   parseFloat(document.getElementById('biaya_lain_lain').value);
    var metode              =   document.getElementById('metode');

    if (((biaya_pengiriman ? biaya_pengiriman : 0) + (beban_angkut ? beban_angkut : 0) + (biaya_lain_lain ? biaya_lain_lain : 0)) > 0) {
        metode.style        =   'display: block';
    } else {
        metode.style        =   'display: none';
    }
}
</script>

<script>
    function angkatan() {
    $(document).ready(function() {
    var row_id  =   $("[name=angkatan]").val();
    // console.log(row_id);
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            url: "{{ route('delivery.get_kandang', ['key' => 'show_kandang']) }}",
            method: "GET",
            data: {
                row_id: row_id,
            },
            success: function(data) {
                $("#show_kandang").html(data);
            }
        });
    });
}
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
    $("[name=purchase]").on("click",function (e) {
            var produk= $(this).attr("data-produk");
            // console.log(produk);
            $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            url: "{{ route('delivery.produk_cek') }}",
            method: "GET",
            data: {
                produk: produk,
            },
            success: function(data) {
                // console.log(data.tipe);
                if (data.tipe == 4) {
                    document.getElementById("angkatan").style="display:block";
                    $(document).on('click', '#check_angkatan', function() {
                    var angkatan_select       =   $("#check_angkatan") ;
                    var kandang_select       =   $("#kandang_select") ;
                    var input_angkatan               =   document.getElementById('angkatan_select');
                    var metodes              =   document.getElementById('metode_angkatan');
                    var tulis_angkatan           =   document.getElementById('tulis_angkatan');
                    var kandang           =   document.getElementById('pilihkandang');
                    var kandang2           =   document.getElementById('pilihkandang2');
                    // var input_kandang           =   $('#input_kandang').val();
                    var angkatan           =   $('[name=angkatan]').val();

                    if (angkatan_select.prop('checked')){
                        // console.log(angkatan);
                        if (angkatan > 0 ) {
                            $("#kdg2").html(`
                            <div id="pilihkandang2">
                                <div class="form-group"  class="kandang_in">
                                    Kandang
                                    <select name="pilih_kandang2" data-width="100%" data-placeholder="Pilih Kandang" class="form-control">
                                        <option value=""></option>
                                        @foreach ($kandang as $row)
                                        <option value="{{ $row->id }}">{{ $row->nama }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>`)
                            kandang.style     =   'display: none' ;
                            // kandang.style     =   'display: none' ;
                            input_angkatan.style     =   'display: none' ;
                            tulis_angkatan.style     =   'display: block' ;
                            tulis_angkatan.value     =   '';
                        }
                        else{
                            $("#kdg2").html(`
                            <div id="pilihkandang2">
                                <div class="form-group"  class="kandang_in">
                                    Kandang
                                    <select name="pilih_kandang2" data-width="100%" data-placeholder="Pilih Kandang" class="form-control">
                                        <option value=""></option>
                                        @foreach ($kandang as $row)
                                        <option value="{{ $row->id }}">{{ $row->nama }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>`)
                            kandang_select.style     =   'display: block' ;
                            input_angkatan.style     =   'display: none' ;
                            tulis_angkatan.style     =   'display: block' ;
                            tulis_angkatan.value     =   '';
                        }
                        $("#metode_angkatan").val("").trigger("change");
                    } else {
                        if (angkatan > 0 ) {
                        kandang.style     =   'display: block' ;
                        input_angkatan.style     =   'display: block' ;
                        tulis_angkatan.style     =   'display: none' ;
                        tulis_angkatan.value     =   '';
                        $("#pilihkandang2").remove()

                        }else{
                            $("#pilihkandang2").remove()
                            input_angkatan.style     =   'display: block' ;
                            tulis_angkatan.style     =   'display: none' ;
                            tulis_angkatan.value     =   '';

                        }

                        $("#metode_angkatan").val("").trigger("change");
                    }
                });
                }
                else{
                    document.getElementById("angkatan").style="display:none";
                    // document.getElementById('pilihkandang').style="display:none";
                    // document.getElementById('pilihkandang2').style="display:none";
                }
            }
        });
            // console.log($(this).val());
        })
</script>

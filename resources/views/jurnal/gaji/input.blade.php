<div class="form-group">
    Tanggal Penggajian
    <input type="date" name="tanggal" id="tanggal" class="form-control">
</div>

<div id="selected">
    <div class="form-group">
        Kandang
        <select name="kandang" id="kandang" data-placeholder="Pilih Kandang" data-width="100%" class="form-control">
            <option value=""></option>
            @foreach (Option::where('slug','kandang')->get() as $row)
            <option value="{{ $row->id }}">{{ $row->nama }}</option>
            @endforeach
        </select>
    </div>
</div>
<div id="selected">
    <div class="form-group">
        Nama Karyawan
        <select name="nama_karyawan" id="nama_karyawan" onchange="hitung_gaji()" data-placeholder="Pilih Nama Karyawan"
            data-width="100%" class="form-control">
            <option value=""></option>
            @foreach ($karyawan as $row)
            <option id="gaji{{ $row->id }}" data-cashbon="{{ $row->total_cashbon }}" data-gaji="{{ $row->gaji_harian }}"
                value="{{ $row->id }}">{{ $row->nama }}</option>
            @endforeach
        </select>
    </div>
</div>

<div id="insert" style="display: none">
    <div class="form-group">
        Nama Karyawan Baru
        <input type="text" name="tulis_karyawan" id="tulis_karyawan" placeholder="Tulis Nama Karyawan Baru"
            autocomplete="off" class="form-control">
    </div>

    <div class="form-group">
        Nomor Telepon
        <input type="number" name="nomor_telepon" id="nomor_telepon" placeholder="Tulis Nomor Telepon"
            autocomplete="off" class="form-control">
    </div>

    <div class="form-group">
        Alamat
        <textarea name="alamat" id="alamat" rows="3" class="form-control"></textarea>
    </div>

    <div class="form-group">
        Tanggal Masuk
        <input type="date" name="tanggal_masuk" id="tanggal_masuk" class="form-control">
    </div>

    <div class="form-group">
        Gaji Per Hari
        <input type="number" min="1" id="gaji_per_hari" class="form-control" onkeyup="hitung_gaji()"
            placeholder="Nominal Gaji Per Hari" data-politespace data-politespace-grouplength="3"
            data-politespace-delimiter="," data-politespace-decimal-mark="." data-politespace-reverse
            autocomplete="off">
    </div>
</div>
<label><input id="check_karyawan" name="check_karyawan" onclick="hitung_gaji()" type="checkbox"> Input nama karyawan
    manual / Tidak ada di list</label>

<div class="form-group">
    Metode Penggajian
    <div class="row mt-1">
        <div class="col pr-1">
            <input type="radio" name="metode_gaji" id="bulanan" value="bulanan" onchange="hitung_gaji()">
            <label for="bulanan">Bulanan</label>
        </div>
        <div class="col pl-1">
            <input type="radio" name="metode_gaji" id="harian" value="harian" onchange="hitung_gaji()">
            <label for="harian">Harian</label>
        </div>
    </div>
    <div id="gaji_bulanan" style="display: none">@include('jurnal.gaji.tab.gaji_bulanan')</div>
    <div id="gaji_harian" style="display: none">@include('jurnal.gaji.tab.gaji_harian')</div>
</div>

<div class="form-group">
    <input type="checkbox" id="lembur" onclick="hitung_gaji()">
    <label for="lembur">Over Time</label>
    <div id="form_lembur" style="display: none">
        <div class="row">
            <div class="col pr-1">
                <input type="radio" name="metode_lembur" id="overjam" value="jam" onchange="hitung_gaji()">
                <label for="overjam">Jam</label>
            </div>
            <div class="col pl-1">
                <input type="radio" name="metode_lembur" id="overharian" value="harian" onchange="hitung_gaji()">
                <label for="overharian">Harian</label>
            </div>
        </div>

        <div id="lembur_jam" style="display: none">@include('jurnal.gaji.tab.lembur_jam')</div>
        <div id="lembur_harian" style="display: none">@include('jurnal.gaji.tab.lembur_harian')</div>
    </div>
</div>

<div class="form-group">
    <input type="checkbox" id="potongan" onclick="hitung_gaji()">
    <label for="potongan">Potongan Gaji</label>
    <div id="form_potongan" style="display: none">
        <div class="form-group">
            Nominal Potongan Gaji
            <input type="number" min="1" id="potong_gaji" onkeyup="hitung_gaji()" class="form-control"
                placeholder="Nominal Potongan Gaji" data-politespace data-politespace-grouplength="3"
                data-politespace-delimiter="," data-politespace-decimal-mark="." data-politespace-reverse
                autocomplete="off">
        </div>
    </div>
</div>

<div class="form-group">
    <input type="checkbox" id="thr" onclick="hitung_gaji()">
    <label for="thr">THR</label>
    <div id="form_thr" style="display: none">
        <div class="form-group">
            Nominal THR
            <input type="number" min="1" id="thr_gaji" onkeyup="hitung_gaji()" class="form-control"
                placeholder="Nominal THR" data-politespace data-politespace-grouplength="3"
                data-politespace-delimiter="," data-politespace-decimal-mark="." data-politespace-reverse
                autocomplete="off">
        </div>
    </div>
</div>


<div class="form-group">
    <input type="checkbox" id="bonus" onclick="hitung_gaji()">
    <label for="bonus">Bonus</label>
    <div id="form_bonus" style="display: none">
        <div class="form-group">
            <div class="row">
                <div class="col-md-6">
                    Nominal Bonus
                    <input type="number" min="1" id="bonus_gaji" onkeyup="hitung_gaji()" class="form-control"
                        placeholder="Nominal bonus">
                </div>
                <div class="col-md-6">
                    keterangan
                    <input type="text" min="1" id="keterangan" class="form-control" placeholder="Keterangan">
                </div>
            </div>
        </div>
    </div>
</div>

<div class="form-group">
    Total Cashbon :
    <div class="font-weight-bold" id="total_cashbond">Rp 0</div>
</div>

<div class="form-group">
    <input type="checkbox" id="cashbon" onclick="hitung_gaji()">
    <label for="cashbon">Bayar Cicilan Cashbon</label>
    <div id="form_cashbon" style="display: none">
        <div class="form-group">
            Nominal Cicilan Cashbon
            <input type="number" min="1" id="nominal_cashbon" onkeyup="hitung_gaji()" class="form-control"
                placeholder="Nominal Cicilan Cashbon" data-politespace data-politespace-grouplength="3"
                data-politespace-delimiter="," data-politespace-decimal-mark="." data-politespace-reverse
                autocomplete="off">
        </div>
    </div>
</div>

<div class="form-group">
    <div class="font-weight-bold">Ringkasan :</div>
    <table>
        <tbody>
            <tr>
                <td>Gaji Di Dapatkan</td>
                <td class="px-2">:</td>
                <td>Rp</td>
                <td class="text-right"><span id="hasil_gaji">0</span></td>
            </tr>
            <tr>
                <td>Over Time</td>
                <td class="px-2">:</td>
                <td>Rp</td>
                <td class="text-right"><span id="hasil_overtime">0</span></td>
            </tr>
            <tr>
                <td>Potongan Gaji</td>
                <td class="px-2">:</td>
                <td>Rp</td>
                <td class="text-right"><span id="hasil_potongan">0</span></td>
            </tr>
            <tr>
                <td>THR</td>
                <td class="px-2">:</td>
                <td>Rp</td>
                <td class="text-right"><span id="hasil_thr">0</span></td>
            </tr>
            <tr>
                <td>Cicil Cashbon</td>
                <td class="px-2">:</td>
                <td>Rp</td>
                <td class="text-right"><span id="hasil_cashbon">0</span></td>
            </tr>
            <tr>
                <td>Bonus</td>
                <td class="px-2">:</td>
                <td>Rp</td>
                <td class="text-right"><span id="hasil_bonus">0</span></td>
            </tr>
            <tr>
                <td class="font-weight-bold">Gaji Diperoleh</td>
                <td class="px-2 font-weight-bold">:</td>
                <td class="font-weight-bold">Rp</td>
                <td class="text-right"><span class="font-weight-bold" id="gaji_peroleh">0</span></td>
            </tr>
        </tbody>
    </table>
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
    <button class="btn btn-block btn-primary" id="selesaikan">Selesaikan</button>
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
    function hitung_gaji() {
    var kary    =   document.getElementById('nama_karyawan').value ;

    var cashbon =   $("#gaji" + kary).attr('data-cashbon') ;
    document.getElementById("total_cashbond").innerHTML =   accounting.formatMoney(cashbon, 'Rp ', 0);

    var check_karyawan  =   $("#check_karyawan:checked").val();
    if (check_karyawan == 'on') {
        var gaji_per_hari   =   document.getElementById("gaji_per_hari").value ;
        document.getElementById("gajihari").value   =   gaji_per_hari ;
        document.getElementById('gajibulan').value  =   (gaji_per_hari * 30) ;
    } else {

        var gaji_per_hari   =   $("#gaji" + kary).attr('data-gaji') ;

        console.log(gaji_per_hari);
        document.getElementById("gajihari").value   =   gaji_per_hari ;
        document.getElementById('gajibulan').value  =   (gaji_per_hari * 30) ;
    }


    var metode_gaji     =   $("input:radio[name=metode_gaji]:checked").val();
    var gaji_bulanan    =   document.getElementById("gaji_bulanan") ;
    var gaji_harian     =   document.getElementById("gaji_harian") ;

    if (metode_gaji == 'bulanan') {
        gaji_bulanan.style  =   "display: block" ;
        gaji_harian.style   =   "display: none" ;
        var gaji            =   document.getElementById('gajibulan').value ;
    }

    if (metode_gaji == 'harian') {
        gaji_bulanan.style  =   "display: none" ;
        gaji_harian.style   =   "display: block" ;
        var gajihari    =   (document.getElementById("harikerja").value > 0) ? document.getElementById("harikerja").value : 0  ;
        var gajidapat   =   (document.getElementById("gajihari").value > 0) ? document.getElementById("gajihari").value : 0  ;
        var gaji        =   (gajidapat * gajihari) ;
    }

    document.getElementById("gaji_dapat").innerHTML =   accounting.formatMoney(gaji, 'Rp ', 0);


    var lembur  =   $("#lembur").prop('checked') ;
    if (lembur == true) {

        document.getElementById('form_lembur').style   =   'display:block' ;
        var metode_lembur   =   $("input:radio[name=metode_lembur]:checked").val();
        var lembur_jam      =   document.getElementById("lembur_jam") ;
        var lembur_harian   =   document.getElementById("lembur_harian") ;

        if (metode_lembur == 'jam') {
            lembur_jam.style    =   "display: block" ;
            lembur_harian.style =   "display: none" ;
            var overjam         =   (document.getElementById("jamover").value > 0) ? document.getElementById("jamover").value : 0  ;
            var overdana        =   (document.getElementById("overdanajam").value > 0) ? document.getElementById("overdanajam").value : 0  ;
            var overtime        =   (overdana * overjam) ;

            document.getElementById("overjam_dapat").innerHTML =   accounting.formatMoney(overtime, 'Rp ', 0);
        }

        if (metode_lembur == 'harian') {
            lembur_jam.style    =   "display: none" ;
            lembur_harian.style =   "display: block" ;
            var hariover        =   (document.getElementById("hariover").value > 0) ? document.getElementById("hariover").value : 0  ;
            var overhari        =   (document.getElementById("overhari").value > 0) ? document.getElementById("overhari").value : 0  ;
            var overtime        =   (overhari * hariover) ;

            document.getElementById("over_dapat").innerHTML =   accounting.formatMoney(overtime, 'Rp ', 0);
        }

    } else {
        document.getElementById('form_lembur').style   =   'display:none' ;
        var overtime        =   0 ;
    }


    var potongan  =   $("#potongan").prop('checked') ;
    if (potongan == true) {
        document.getElementById('form_potongan').style   =   'display:block' ;
        var potong_gaji =   document.getElementById('potong_gaji').value ;
    } else {
        document.getElementById('form_potongan').style   =   'display:none' ;
        var potong_gaji =   0 ;
    }

    var thr  =   $("#thr").prop('checked') ;
    if (thr == true) {
        document.getElementById('form_thr').style   =   'display:block' ;
        var thr_gaji =   document.getElementById('thr_gaji').value ;
    } else {
        document.getElementById('form_thr').style   =   'display:none' ;
        var thr_gaji =   0 ;
    }
    var bonus = $("#bonus").prop('checked') ;
    if (bonus == true) {
    document.getElementById('form_bonus').style = 'display:block' ;
    var bonus_gaji = document.getElementById('bonus_gaji').value ;
    } else {
    document.getElementById('form_bonus').style = 'display:none' ;
    var bonus_gaji = 0 ;
    }

    var cashbon =   $("#cashbon").prop('checked') ;
    if (cashbon == true) {
        document.getElementById('form_cashbon').style   =   'display:block' ;
        var potong_bon  =   document.getElementById('nominal_cashbon').value ;
    } else {
        document.getElementById('form_cashbon').style   =   'display:none' ;
        var potong_bon  =   0 ;
    }

    document.getElementById("hasil_gaji").innerHTML     =   accounting.formatMoney(gaji, '', 0);
    document.getElementById("hasil_overtime").innerHTML =   accounting.formatMoney(overtime, '', 0);
    document.getElementById("hasil_potongan").innerHTML =   accounting.formatMoney(potong_gaji, '', 0);;
    document.getElementById("hasil_thr").innerHTML      =   accounting.formatMoney(thr_gaji, '', 0);;
    document.getElementById("hasil_cashbon").innerHTML  =   accounting.formatMoney(potong_bon, '', 0);;
    document.getElementById("hasil_bonus").innerHTML = accounting.formatMoney(bonus_gaji, '', 0);
    document.getElementById("gaji_peroleh").innerHTML   =   accounting.formatMoney((parseFloat(gaji) + parseFloat(overtime)) - (parseFloat(potong_gaji) + parseFloat(potong_bon)) + (parseFloat(thr_gaji))+(parseFloat(bonus_gaji)), '', 0);;
}
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

<script>
    $(document).ready(function() {
        $(document).on('click', '#check_karyawan', function() {
            var selected        =   $("#check_karyawan") ;
            var select          =   document.getElementById('selected');
            var insert          =   document.getElementById('insert');

            if (selected.prop('checked')){
                select.style    =   'display: none' ;
                insert.style    =   'display: block' ;
            } else {
                select.style    =   'display: block' ;
                insert.style    =   'display: none' ;
            }
        });
    });
</script>

<div class="row">
    <div class="col-md-6 pr-1">
        <div class="form-group">
            Jumlah Hari Kerja
            <input type="number" min="1" id="harikerja" onkeyup="hitung_gaji()" class="form-control" placeholder="Tulis Jumlah Hari">
        </div>
    </div>

    <div class="col-md-6 pl-1">
        <div class="form-group">
            Gaji Per Hari
            <input type="number" min="1" id="gajihari" onkeyup="hitung_gaji()" class="form-control" placeholder="Nominal Gaji Per Hari" data-politespace data-politespace-grouplength="3" data-politespace-delimiter="," data-politespace-decimal-mark="." data-politespace-reverse autocomplete="off">
        </div>
    </div>
</div>

<div class="form-group">
    Total Gaji Didapatkan
    <div id="gaji_dapat" class="font-weight-bold">Rp 0</div>
</div>

<script>
    jQuery( function(){
        jQuery( document ).trigger( "enhance" );
    });
</script>

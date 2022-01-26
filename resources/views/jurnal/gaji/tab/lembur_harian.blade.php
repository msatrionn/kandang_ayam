<div class="row">
    <div class="col-md-6 pr-1">
        <div class="form-group">
            Jumlah Hari Over Time
            <input type="number" min="1" id="hariover" onkeyup="hitung_gaji()" class="form-control" placeholder="Tulis Jumlah Hari">
        </div>
    </div>

    <div class="col-md-6 pl-1">
        <div class="form-group">
            Nominal Per Hari
            <input type="number" min="1" id="overhari" onkeyup="hitung_gaji()" class="form-control" placeholder="Nominal Over Time" data-politespace data-politespace-grouplength="3" data-politespace-delimiter="," data-politespace-decimal-mark="." data-politespace-reverse autocomplete="off">
        </div>
    </div>
</div>

<div class="form-group">
    Total Tambahan Over Time Didapatkan
    <div id="over_dapat" class="font-weight-bold">Rp 0</div>
</div>

<script>
    jQuery( function(){
        jQuery( document ).trigger( "enhance" );
    });
</script>

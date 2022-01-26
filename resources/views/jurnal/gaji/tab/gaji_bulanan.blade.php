<div class="form-group">
    Nominal Gaji Bulanan
    <input type="number" min="1" id="gajibulan" class="form-control" onkeyup="hitung_gaji()" placeholder="Tulis Nominal Gaji" data-politespace data-politespace-grouplength="3" data-politespace-delimiter="," data-politespace-decimal-mark="." data-politespace-reverse autocomplete="off">
</div>

<script>
    jQuery( function(){
        jQuery( document ).trigger( "enhance" );
    });
</script>

<div class="form-group">
    Item Produk
    <select id="item" data-placeholder="Pilih Item Produk" data-width="100%" class="form-control select2">
        <option value=""></option>
        @foreach ($produk as $row)
        <option value="{{ $row->id }}">[{{ $row->tipeset->nama }}] {{ $row->nama }}</option>
        @endforeach
    </select>
</div>

<div class="form-group">
    Jumlah
    <input type="number" step="0.1" min="0" placeholder="Tuliskan Jumlah" id="jumlah" class="form-control">
</div>

<div class="form-group">
    Tanggal
    <input type="date" id="tanggal" class="form-control">
</div>

<div class="form-group">
    <button class="btn btn-block btn-primary" id="input_cutoff">Submit</button>
</div>

<script>
$('.select2').select2({
    theme: 'bootstrap4',
})
</script>

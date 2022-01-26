<div class="form-group">
    Nama Karyawan
    <select id="karyawan" onchange="pilih_karyawan()" data-placeholder="Pilih Karyawan" data-width="100%" class="form-control select2">
        <option value=""></option>
        @foreach ($karyawan as $row)
        <option value="{{ $row->id }}">{{ $row->nama }}</option>
        @endforeach
    </select>
</div>

<script>
    $('.select2').select2({
        theme: 'bootstrap4',
    })
</script>

<link rel="stylesheet" href="{{ asset('assets/vendor/select2/select2-bootstrap4.min.css') }}">
<div id="kandang-select">
    <div class="form-group">
        Kandang
        <select name="kandang[]" id="kandang" class="form-control select2" data-width="100%"
            data-placeholder="Pilih Kandang">
            <option value=""></option>
            <option value="ALL">Tanpa Kandang </option>
            @foreach ($kandang as $id => $row)
            <option value="{{ $row->kandang }}">{{ $row->farm->nama }}</option>
            @endforeach
        </select>
    </div>
</div>
<script src="{{ asset('assets/vendor/select2/select2.js') }}"></script>

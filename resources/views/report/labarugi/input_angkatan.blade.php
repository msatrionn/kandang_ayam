<link rel="stylesheet" href="{{ asset('assets/vendor/select2/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/vendor/select2/select2-bootstrap4.min.css') }}">
<label for="">Angkatan</label>
<select name="angkatan" class="form-control select2">
    <option value="" aria-readonly="true">Pilih angkatan</option>
    @foreach ($angkatan as $item)
    <option value="{{ $item->angkatan }}">{{ $item->angkatan }}</option>
    @endforeach
</select>

<script src="{{ asset('assets/vendor/select2/select2.js') }}"></script>

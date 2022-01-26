<div class="form-group">
    Keterangan Tanggal
    <input type="text" value="{{ Tanggal::date(Carbon\Carbon::parse($data->tanggal)->addDays(($hari - 1))) }}" disabled class="form-control">
</div>

<div class="form-group">
    Tulis Keterangan
    <textarea name="keterangan_catatan" id="keterangan_catatan" rows="4" class="form-control">{{ $catatan->data_catatan ?? '' }}</textarea>
</div>

<div class="form-group">
    <button type="button" id="input_keterangan" class="btn btn-block btn-primary">Submit</button>
</div>

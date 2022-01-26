<div class="form-group">
    Populasi Tanggal
    <input type="text" value="{{ Tanggal::date(Carbon\Carbon::parse($data->tanggal)->addDays(($hari - 1))) }}" disabled class="form-control">
</div>

<div class="row">
    <div class="col-md-4 col-12 pr-md-1">
        <div class="form-group">
            <div class="small bg-light p-1 text-center">Mati</div>
            <input type="number" name="populasi_mati" id="populasi_mati" value="{{ $record->populasi_mati ?? '' }}" class="form-control text-center rounded-0">
        </div>
    </div>
    <div class="col-md-4 col-12 px-md-1">
        <div class="form-group">
            <div class="small bg-light p-1 text-center">Afkir</div>
            <input type="number" name="populasi_afkir" id="populasi_afkir" value="{{ $record->populasi_afkir ?? '' }}" class="form-control text-center rounded-0">
        </div>
    </div>
    <div class="col-md-4 col-12 px-md-1">
        <div class="form-group">
            <div class="small bg-light p-1 text-center">Panen</div>
            <input type="number" name="populasi_panen" id="populasi_panen" value="{{ $record->populasi_panen ?? '' }}" class="form-control text-center rounded-0">
        </div>
    </div>
</div>

<div class="form-group">
    <button type="button" id="input_populasi" class="btn btn-block btn-primary">Submit</button>
</div>

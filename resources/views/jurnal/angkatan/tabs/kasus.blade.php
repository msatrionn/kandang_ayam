<div class="row">
    <div class="col-md-6 pr-md-1">
        <div class="form-group">
            Tanggal
            <input type="date" name="tamggal_penyaklit" id="tanggal_penyakit" class="form-control">
        </div>

        <div class="form-group">
            Nama Penyakit
            <div id="penyakit_selected">
                <select name="penyakit" data-placeholder="Pilih Penyakit" id="penyakit" class="form-control">
                    <option value=""></option>
                    @foreach ($penyakit as $row)
                    <option value="{{ $row->id }}">{{ $row->nama }}</option>
                    @endforeach
                </select>
            </div>
            <div id="penyakit_input" style="display: none">
                <input type="text" name="input_penyakit" placeholder="Tulis Nama Penyakit" id="input_penyakit" class="form-control">
            </div>
            <label class="mt-2"><input id="check_penyakit" type="checkbox"> Input nama penyakit manual / Tidak ada di list</label>
        </div>
    </div>
    <div class="col-md-6 pl-md-1">
        <div class="form-group">
            <div>Unggah Foto</div>
            <input type="file" name="unggah_foto" id="unggah_foto">
        </div>

        <div class="form-group">
            Keterangan
            <textarea name="keterangan_penyakit" id="keterangan_penyakit" rows="3" class="form-control"></textarea>
        </div>
    </div>
</div>

<div class="form-group">
    <button type="button" id='submit_penyakit' class="btn btn-block btn-primary">Submit</button>
</div>

<div class="border-top pt-3 mt-3">
    @foreach ($daftar_penyait as $row)
    <div class="border mb-2 p-1">
        <div class="row">
            <div class="col-md-3 col-6 pr-1">
                <small>Tanggal</small>
                <div>{{ Tanggal::date($row->tanggal) }}</div>
            </div>
            <div class="col-md-3 col-6 pl-1 px-md-1">
                <small>Nama Penyakit</small>
                <div>{{ $row->penyakit->nama }}</div>
            </div>
            <div class="col-md-6 col-12 pl-md-1">
                <small>Keterangan</small>
                <div>@if ($row->foto)
                    <a target="_blank" href="{{ asset($row->foto) }}">Klik Lihat Foto</a> -
                @endif {{ $row->keterangan }}</div>
            </div>
        </div>
    </div>
    @endforeach
</div>

<script>
    $(document).ready(function() {
        $(document).on('click', '#check_penyakit', function() {
            var input       =   $("#check_penyakit") ;
            var selected    =   document.getElementById('penyakit_selected');
            var write       =   document.getElementById('penyakit_input');

            var select      =   document.getElementById('penyakit');
            var text        =   document.getElementById('input_penyakit');

            if (input.prop('checked')){
                selected.style  =   'display: none' ;
                write.style     =   'display: block' ;
                text.value      =   '';
                $("#penyakit").val("").trigger("change");
            } else {
                selected.style  =   'display: block' ;
                write.style     =   'display: none' ;
                text.value      =   '';
                $("#penyakit").val("").trigger("change");
            }
        });
    });
</script>

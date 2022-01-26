<div class="form-group">
    @php
        $exp    =   json_decode($kandang->farm->json_data);
    @endphp
    Kandang : {{ $kandang->farm->nama }}<br>
    Doc In : {{ Tanggal::date($kandang->tanggal) }}
</div>

<div class="row">
    <div class="col-md-3">
        <div class="form-group">
            Jumlah Ayam Tersedia di Kandang
            <input type="text" disabled value="{{ number_format($kandang->populasi_akhir) }}" class="form-control">
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            Tanggal Mutasi
            <input type="date" name="tanggal_mutasi" id='tanggal_mutasi' class="form-control" min="1">
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            Jumlah Ayam Dimutasi
            <input type="number" name="jumlah_mutasi" id='jumlah_mutasi' class="form-control" min="1">
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            Penempatan Kandang
            <select name="penempatan_kandang" id="penempatan_kandang" class="form-control select2" data-placeholder="Pilih Kandang" data-width="100%">
                <option value=""></option>
                @foreach ($daftar_kandang as $row)
                @php
                    $exp    =   json_decode($row->json_data) ;
                @endphp
                <option value="{{ $row->id }}">{{ $row->nama }}</option>
                @endforeach
            </select>
        </div>
    </div>
</div>

<button type="button" id="mutasi_kandang" class="btn btn-primary btn-block">Submit</button>

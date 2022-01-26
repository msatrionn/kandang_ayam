<div class="card-body">
    @if (COUNT($data))
    <div class="accordion" id="accrodion">
        @foreach ($data as $item)
        <div class="card-header" id="heading{{ $item->id }}">
            <span data-toggle="collapse" data-target="#collapse{{ $item->id }}" aria-expanded="true" aria-controls="collapse{{ $item->id }}">
                <div class="py-2 px-3 cursor">{{ $item->nama }}</div>
            </span>
        </div>

        <div id="collapse{{ $item->id }}" class="collapse {{ $tab ? ($tab == $item->id ? 'show' : '') : ''  }}" aria-labelledby="heading{{ $item->id }}" data-parent="#accrodion">
            <div class="card-body">
                <div class="border p-2">
                    <div class="row">
                        <div class="col pr-1">
                            <div class="form-group">
                                Nama Strain
                                <input type="text" id="nama_strain{{ $item->id }}" value="{{ $item->nama }}" class="form-control" autocomplete="off">
                            </div>
                        </div>
                        <div class="col-4 pl-1">
                            &nbsp;
                            <button type="button" data-id="{{ $item->id }}" class="ubah_strain btn btn-primary btn-block">Ubah</button>
                        </div>
                    </div>
                </div>

                <div class="mt-2 pt-2">
                    <div class="row">
                        <div class="col-md-4 pr-md-2 text-center">
                            <div class="radio-toolbar">
                                <input type="radio" name="jenis" value="gram" id="pakan_gr_hr{{ $item->id }}">
                                <label for="pakan_gr_hr{{ $item->id }}">Konsumsi Pakan Standar Gram/Hari</label>

                                <input type="radio" name="jenis" value="global" id="pakan_global{{ $item->id }}">
                                <label for="pakan_global{{ $item->id }}">Konsumsi Pakan Standar Global</label>

                                <input type="radio" name="jenis" value="bb" id="bb_standar{{ $item->id }}">
                                <label for="bb_standar{{ $item->id }}">Berat Badan Standar</label>
                            </div>
                        </div>
                        <div class="col-md-8 pl-md-2">
                            <div class="border rounded p-2">
                                <div class="row">
                                    <div class="col pr-1">
                                        <div class="form-group">
                                            <div class="small text-center bg-light py-1 px-2">Minggu</div>
                                            <input type="number" name="minggu_umur" id="minggu_umur{{ $item->id }}" placeholder="Minggu" class="form-control rounded-0 text-center">
                                        </div>
                                    </div>
                                    <div class="col px-1">
                                        <div class="form-group">
                                            <div class="small text-center bg-light py-1 px-2">Mulai</div>
                                            <input type="number" name="mulai_umur" id="mulai_umur{{ $item->id }}" placeholder="Umur" class="form-control rounded-0 text-center">
                                        </div>
                                    </div>
                                    <div class="col px-1">
                                        <div class="form-group">
                                            <div class="small text-center bg-light py-1 px-2">Sampai</div>
                                            <input type="number" name="sampai_umur" id="sampai_umur{{ $item->id }}" placeholder="Umur" class="form-control rounded-0 text-center">
                                        </div>
                                    </div>
                                    <div class="col pl-1">
                                        <div class="form-group">
                                            <div class="small text-center bg-light py-1 px-2">Angka</div>
                                            <input type="number" name="standar" step="0.01" id="standar{{ $item->id }}" placeholder="Standar" class="form-control rounded-0 text-center">
                                        </div>
                                    </div>
                                </div>
                                <button data-id="{{ $item->id }}" class="add_standar btn rounded-0 mt-1 btn-primary btn-block">Submit</button>
                            </div>
                        </div>
                    </div>

                    <div class="mt-2 pt-2">
                        {!! Strain::data_strain('gram', $item->id) !!}
                        {!! Strain::data_strain('global', $item->id) !!}
                        {!! Strain::data_strain('bb', $item->id) !!}
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @endif
</div>

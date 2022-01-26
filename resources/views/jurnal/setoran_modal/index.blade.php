@extends('layouts.main')

@section('header')
<link rel="stylesheet" href="{{ asset('js/vendor/politespace/politespace.css') }}">
<link rel="stylesheet" href="{{ asset('assets/vendor/select2/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/vendor/select2/select2-bootstrap4.min.css') }}">
@endsection

@section('footer')
<script src="{{ asset('js/vendor/politespace/libs/libs.js') }}"></script>
<script src="{{ asset('js/vendor/politespace/politespace.js') }}"></script>
<script src="{{ asset('js/vendor/politespace/politespace-init.js') }}"></script>

<script src="{{ asset('assets/vendor/select2/select2.js') }}"></script>

<script>
    $(document).ready(function() {
        $(document).on('click', '#check_kas', function() {
            var metode_select       =   $("#check_kas") ;
            var input               =   document.getElementById('metode_select');
            var metode              =   document.getElementById('setoran_kas');
            var tulis_kas           =   document.getElementById('tulis_kas');

            if (metode_select.prop('checked')){
                input.style         =   'display: none' ;
                tulis_kas.style     =   'display: block' ;
                tulis_kas.value     =   '';
                $("#setoran_kas").val("").trigger("change");
            } else {
                input.style         =   'display: block' ;
                tulis_kas.style     =   'display: none' ;
                tulis_kas.value     =   '';
                $("#setoran_kas").val("").trigger("change");
            }
        });
    });
</script>
@endsection

@section('title', 'Jurnal Setoran Modal')

@section('content')
<div class="row">
    <div class="col-lg-4 col-md-6">
        <div class="card">
            <div class="card-header">Input Modal</div>
            <form action="{{ route('setormodal.store') }}" method="POST">
                @csrf
                <div class="card-body">
                    <div class="form-group">
                        Tanggal
                        <input type="date" name="tanggal" class="form-control" value="{{ old('tanggal') }}" id="tanggal" placeholder="Tuliskan Tanggal" autocomplete="off">
                        @error('tanggal') <div class="small text-danger">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-group">
                        Setoran Kas
                        <div id="metode_select">
                            <select name="setoran_kas" id='setoran_kas' data-placeholder="Pilih Kas" class="form-control">
                                <option value=""></option>
                                @foreach ($payment as $id => $row)
                                <option value="{{ $id }}" {{ old('setoran_kas') == $id ? 'selected' : '' }}>{{ $row }}</option>
                                @endforeach
                            </select>
                        </div>
                        <input type="text" style="{{ old('check_kas') ? '' : 'display: none' }}" name="tulis_kas" id="tulis_kas" value="{{ old('tulis_kas') }}" placeholder="Tulis Kas" autocomplete="off" class="form-control">
                        <label class="mt-2"><input id="check_kas" name="check_kas" {{ old('check_kas') ? 'checked' : '' }} type="checkbox"> Input kas manual / Tidak ada di list</label>
                        @error('setoran_kas') <div class="small text-danger">{{ $message }}</div> @enderror
                        @error('tulis_kas') <div class="small text-danger">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-group">
                        Nominal Setor Modal
                        <input type="number" name="nominal_setor_modal" class="form-control" value="{{ old('nominal_setor_modal') }}" id="nominal_setor_modal" placeholder="Tuliskan Nominal Setor Modal" autocomplete="off" data-politespace data-politespace-grouplength="3" data-politespace-delimiter="," data-politespace-decimal-mark="." step="0.01" data-politespace-reverse>
                        @error('nominal_setor_modal') <div class="small text-danger">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-group">
                        Jenis Modal
                        <div>
                            <input type="radio" value="in" {{ old('jenis_modal') == "in" ? 'checked' : '' }} name="jenis_modal" id="masuk">
                            <label for="masuk">Modal Masuk</label>
                            &nbsp; &nbsp;
                            <input type="radio" value="out" {{ old('jenis_modal') == "out" ? 'checked' : '' }} name="jenis_modal" id="keluar">
                            <label for="keluar">Modal Keluar</label>
                        </div>
                        @error('jenis_modal') <div class="small text-danger">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-group">
                        Keterangan
                        <textarea name="keterangan" id="keterangan" class="form-control" placeholder="Tuliskan Keterangan" rows="3">{{ old('keterangan') }}</textarea>
                        @error('keterangan') <div class="small text-danger">{{ $message }}</div> @enderror
                    </div>
                </div>
                <div class="card-footer text-right">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>
    <div class="col-lg-8 col-md-6">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('setormodal.index') }}" method="get">
                    <input type="hidden" name="key" value="unduh">
                    <div class="row">
                        <div class="col-lg col-6"><input type="date" name="mulai" class="form-control"></div>
                        <div class="col-lg col-6"><input type="date" name="selesai" class="form-control"></div>
                        <div class="col-xl-auto col-12 pl-xl-1 mt-3 mt-xl-0">
                            <button type="submit" class="btn btn-outline-success btn-block"><i class="fa fa-file-excel-o"></i> Unduh</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="card">
            <div class="card-header">Riwayat Input Modal</div>
            <div class="card-body">
                @foreach ($data as $row)
                    <div class="border rounded p-2 mb-2">
                        <div class="row">
                            <div class="col pr-1">
                                <div>{{ Tanggal::date($row->tanggal) }}</div>
                                {{ $row->keterangan }}
                            </div>
                            <div class="col-auto pl-1 text-right {{ $row->jenis == 'tarik_modal' ? 'text-danger' : 'text-success' }}">
                                {{ $row->nominal_transaksi }}<br>
                                {{ $row->method->nama }}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection

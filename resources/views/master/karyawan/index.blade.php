@extends('layouts.main')

@section('title', 'Daftar Karyawan')

@section('header')
<link rel="stylesheet" href="{{ asset('js/vendor/politespace/politespace.css') }}">
@endsection

@section('footer')

<script src="{{ asset('js/vendor/politespace/libs/libs.js') }}"></script>
<script src="{{ asset('js/vendor/politespace/politespace.js') }}"></script>
<script src="{{ asset('js/vendor/politespace/politespace-init.js') }}"></script>
<script>
    $(".pagination").attr('class', 'pagination pagination-sm pt-2');
</script>

<script>
    $('#yes').click(function() {
        if($(this).is(':checked')) {
            $('#no').prop( "checked", false );
            $('#makan').show()
            }
    });
    $('#no').click(function() {
        if($(this).is(':checked')) {
            $('#yes').prop( "checked", false );
            $('#makan').hide()
        }
    });
</script>

<script>
    $('.makan_edit_hide').hide()
    $('.yes_edit').click(function() {
        if($(this).is(':checked')) {
            $('.no_edit').prop( "checked", false );
            $('.makan_edit').show()
            $('.makan_edit_hide').show()
            }
    });
    $('.no_edit').click(function() {
        if($(this).is(':checked')) {
            $('.yes_edit').prop( "checked", false );
            $('.makan_edit').hide()
            $('.makan_edit_hide').hide()
            $('[name=uang_makan]').val("")
        }
    });
</script>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">Tambah Karyawan</div>
            <div class="card-body">
                <form action="{{ route('karyawan.store') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        Nama Karyawan
                        <input type="text" name="nama_karyawan" class="form-control" id="nama_karyawan"
                            placeholder="Tuliskan Nama Karyawan" value="{{ old('nama_karyawan') }}" autocomplete="off">
                        @error("nama_karyawan") <div class="small text-danger">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-group">
                        Nomor Telepon
                        <input type="number" name="nomor_telepon" class="form-control" id="nomor_telepon"
                            placeholder="Tuliskan Nomor Telepon" value="{{ old('nomor_telepon') }}" autocomplete="off">
                        @error("nomor_telepon") <div class="small text-danger">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-group">
                        Alamat
                        <textarea name="alamat" class="form-control" id="alamat" rows="3">{{ old('alamat') }}</textarea>
                        @error("alamat") <div class="small text-danger">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-group">
                        Tanggal Masuk
                        <input type="date" name="tanggal_masuk" class="form-control" value="{{ old('tanggal_masuk') }}"
                            autocomplete="off">
                        @error("tanggal_masuk") <div class="small text-danger">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-group">
                        Gaji Per Hari
                        <input type="number" min="1" name="gaji_per_hari" class="form-control"
                            placeholder="Nominal Gaji Per Hari" data-politespace data-politespace-grouplength="3"
                            data-politespace-delimiter="," data-politespace-decimal-mark="." data-politespace-reverse
                            autocomplete="off">
                        @error("nama_karyawan") <div class="small text-danger">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-group">
                        Menginap
                        <div class="row mt-1">
                            <div class="col-md-3 text-center">
                                <input type="radio" id="yes" name="true" checked>
                                <label>Ya</label>
                            </div>
                            <div class="col-md-4 text-center">
                                <input type="radio" id="no" name="false">
                                <label>Tidak</label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group" id="makan">
                        Uang makan
                        <input type="number" name="uang_makan" class="form-control" id="uang_makan"
                            placeholder="Nominal" value="{{ old('uang_makan') }}" autocomplete="off">
                        @error("uang_makan") <div class="small text-danger">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-group text-right">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">Daftar Karyawan</div>
            <div class="card-body">

                <form action="{{ route('karyawan.index') }}" method="get">
                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                Pencarian
                                <input type="text" name="q" class="form-control" placeholder="Cari..." value="{{ $q }}"
                                    autocomplete="off">
                            </div>

                        </div>
                        <div class="col-auto">
                            &nbsp;
                            <button type="submit" class="btn btn-block px-4 btn-primary">Cari</button>
                        </div>
                    </div>
                </form>

                @if (COUNT($data))
                @foreach ($data as $row)
                <div class="border rounded p-2 mb-2">
                    <div class="row">
                        <div class="col pr-1">{{ $row->nama }}</div>
                        <div class="col-auto pl-1">
                            {{-- <form action="{{ route('karyawan.detail') }}" class="d-inline-block mr-1"
                                method="POST">
                                @csrf @method('put') <input type="hidden" name="x_code" value="{{ $row->id }}">
                                <button type="submit" class="btn btn-link p-0 text-danger">
                                    <i class="fa fa-file-pdf-o"></i>
                                </button>
                            </form> --}}
                            <button type=" button" class="btn btn-link text-primary p-0 btn-sm" data-toggle="modal"
                                data-target="#modal{{ $row->id }}">
                                <i class="icon-note"></i>
                            </button>
                            <form action="{{ route('karyawan.destroy') }}" class="d-inline-block mr-1" method="POST">
                                @csrf @method('delete') <input type="hidden" name="x_code" value="{{ $row->id }}"">

                                <button type=" submit" class="btn btn-link text-danger p-0 btn-sm">
                                <i class="icon-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col pr-1">Rp {{ number_format($row->gaji_harian) }}</div>
                    </div>
                </div>

                <div class="modal fade" id="modal{{ $row->id }}" tabindex="-1"
                    aria-labelledby="modal{{ $row->id }}Label" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="modal{{ $row->id }}Label">Ubah Karyawan</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <form action="{{ route('karyawan.update') }}" method="POST">
                                @csrf @method('patch') <input type="hidden" name="x_code" value="{{ $row->id }}"">
                                <div class=" modal-body">
                                <div class="form-group">
                                    Nama Karyawan
                                    <input type="text" name="nama_karyawan" class="form-control"
                                        value="{{ $row->nama }}" placeholder="Tuliskan Nama Karyawan"
                                        autocomplete="off">
                                </div>

                                <div class="form-group">
                                    Nomor Telepon
                                    <input type="number" name="nomor_telepon" class="form-control"
                                        placeholder="Tuliskan Nomor Telepon" value="{{ $row->telepon }}"
                                        autocomplete="off">
                                </div>

                                <div class="form-group">
                                    Alamat
                                    <textarea name="alamat" class="form-control" id="alamat"
                                        rows="3">{{ $row->alamat }}</textarea>
                                </div>

                                <div class="form-group">
                                    Tanggal Masuk
                                    <input type="date" name="tanggal_masuk" class="form-control"
                                        placeholder="Tuliskan Nomor Telepon" value="{{ $row->tanggal_masuk }}"
                                        autocomplete="off">
                                </div>

                                <div class="form-group">
                                    Gaji Per Hari
                                    <input type="number" min="1" name="gaji_per_hari" class="form-control"
                                        value="{{ $row->gaji_harian }}" placeholder="Nominal Gaji Per Hari"
                                        data-politespace data-politespace-grouplength="3" data-politespace-delimiter=","
                                        data-politespace-decimal-mark="." data-politespace-reverse autocomplete="off">
                                </div>
                                @if ($row->uang_makan)
                                <div class="form-group">
                                    Menginap
                                    <div class="row mt-1">
                                        <div class="col-md-3 text-center">
                                            <input type="radio" class="yes_edit" name="true" checked>
                                            <label>Ya</label>
                                        </div>
                                        <div class="col-md-4 text-center">
                                            <input type="radio" class="no_edit" name="false">
                                            <label>Tidak</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group makan_edit">
                                    Uang makan
                                    <input type="number" name="uang_makan" class="form-control" id="uang_makan_edit"
                                        placeholder="Nominal" value="{{ $row->uang_makan }}" autocomplete="off">
                                    @error("uang_makan") <div class="small text-danger">{{ $message }}</div> @enderror
                                </div>
                                @endif
                                @if($row->uang_makan == 0 or empty($row->uang_makan) or $row->uang_makan==null)

                                <div class="form-group">
                                    Menginap
                                    <div class="row mt-1">
                                        <div class="col-md-3 text-center">
                                            <input type="radio" class="yes_edit" name="true">
                                            <label>Ya</label>
                                        </div>
                                        <div class="col-md-4 text-center">
                                            <input type="radio" class="no_edit" name="false" checked>
                                            <label>Tidak</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group makan_edit_hide">
                                    Uang makan
                                    <input type="number" name="uang_makan" class="form-control" id="uang_makan_edit"
                                        placeholder="Nominal" value="{{ $row->uang_makan }}" autocomplete="off">
                                    @error("uang_makan") <div class="small text-danger">{{ $message }}</div> @enderror
                                </div>
                                @endif

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Save changes</button>
                        </div>
                        </form>
                    </div>
                </div>
            </div>
            @endforeach

            {{ $data->appends($_GET)->onEachSide(0)->links() }}
            @endif
        </div>
    </div>
</div>
</div>
@endsection

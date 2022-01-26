@extends('layouts.main')

@section('title', 'Produk Jual')

@section('header')
<link rel="stylesheet" href="{{ asset('assets/vendor/select2/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/vendor/select2/select2-bootstrap4.min.css') }}">
@endsection

@section('footer')
<script src="{{ asset('assets/vendor/select2/select2.js') }}"></script>
<script>
$(".pagination").attr('class', 'pagination pagination-sm pt-2');
</script>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                Buat Produk
            </div>
            <form action="{{ route('produkjual.store') }}" method="POST">
            @csrf
            <div class="card-body">

                <div class="form-group">
                    Nama Produk
                    <input type="text" name="nama_produk" class="form-control" value="{{ old('nama_produk') }}" id="nama_produk" placeholder="Tuliskan Nama Produk" autocomplete="off">
                    @error('nama_produk') <div class="small text-danger">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    Tipe Produk
                    <select name="tipe" class="form-control select2" id="tipe" data-placeholder="Pilih Tipe Produk">
                        <option value=""></option>
                        @foreach ($tipe as $id => $nama)
                        <option value="{{ $id }}" {{ (old('tipe') == $id) ? 'selected' : '' }}>{{ $nama }}</option>
                        @endforeach
                    </select>
                    @error("tipe") <div class="small text-danger">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    Satuan
                    <select name="satuan" class="form-control" id="satuan" data-placeholder="Pilih Satuan">
                        <option value=""></option>
                        @foreach ($satuan as $id => $nama)
                        <option value="{{ $id }}" {{ (old('satuan') == $id) ? 'selected' : '' }}>{{ $nama }}</option>
                        @endforeach
                    </select>
                    @error("satuan") <div class="small text-danger">{{ $message }}</div> @enderror
                </div>

            </div>
            <div class="card-footer text-right">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
            </form>
        </div>
    </div>

    <div class="col-lg-8">
        @if (COUNT($data))
        <div class="card">
            <div class="card-header">
                Daftar Produk Jual
            </div>
            <div class="card-body">
                <form action="{{ route('produkjual.index') }}" method="get">
                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                Pencarian
                                <input type="text" name="q" class="form-control" placeholder="Cari..." value="{{ $q }}" autocomplete="off">
                            </div>

                        </div>
                        <div class="col-auto">
                            &nbsp;
                            <button type="submit" class="btn btn-block px-4 btn-primary">Cari</button>
                        </div>
                    </div>
                </form>

                @foreach ($data as $row)
                <div class="border rounded p-2 mb-2">
                    <div class="row">
                        <div class="col text-bold pr-1">
                            <span class="small text-primary text-bold">{{ $row->tipeset->nama }}</span>
                        </div>
                        <div class="col-auto pl-1">{{ $row->tipesatuan->nama }}</div>
                    </div>
                    <div class="row">
                        <div class="col pr-1">{{ $row->nama }}</div>
                        <div class="col-auto pl-1">
                            @if (COUNT($row->relatepurc) < 1)
                            <form action="{{ route('produkjual.destroy') }}" method="POST">
                                @csrf @method('delete') <input type="hidden" name="x_code" value="{{ $row->id }}"">
                            @endif
                                <button type="button" class="btn btn-link text-primary p-0" data-toggle="modal" data-target="#modal{{ $row->id }}">
                                    <i class="icon-note"></i>
                                </button>
                            @if (COUNT($row->relatepurc) < 1)
                                <button type="submit" class="btn btn-link text-danger p-0">
                                    <i class="icon-trash"></i>
                                </button>
                            </form>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="modal fade" id="modal{{ $row->id }}" tabindex="-1" aria-labelledby="modal{{ $row->id }}Label" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="modal{{ $row->id }}Label">Ubah Produk</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <form action="{{ route('produkjual.update') }}" method="POST">
                                @csrf @method('patch') <input type="hidden" name="x_code" value="{{ $row->id }}"">
                                <div class="modal-body">
                                    <div class="form-group">
                                        Nama Produk
                                        <input type="text" name="nama_produk" value="{{ $row->nama }}" class="form-control" placeholder="Tuliskan Nama Produk" autocomplete="off">
                                    </div>

                                    <div class="form-group">
                                        Tipe Produk
                                        <select name="tipe" class="form-control" data-placeholder="Pilih Tipe Produk">
                                            <option value=""></option>
                                            @foreach ($tipe as $id => $nama)
                                            <option value="{{ $id }}" {{ ($row->tipe == $id) ? 'selected' : '' }}>{{ $nama }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        Satuan
                                        <select name="satuan" class="form-control" data-placeholder="Pilih Satuan</">
                                            <option value="">option>
                                            @foreach ($satuan as $id => $nama)
                                            <option value="{{ $id }}" {{ ($row->satuan == $id) ? 'selected' : '' }}>{{ $nama }}</option>
                                            @endforeach
                                        </select>
                                    </div>
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
            </div>
        </div>
        @endif
    </div>
</div>
@endsection

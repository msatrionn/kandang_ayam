@extends('layouts.main')

@section('title', 'Daftar Pembayaran')

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
            <div class="card-header">Tambah Pembayaran</div>
            <div class="card-body">
                <form action="{{ route('payment.store') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        Nama Pembayaran
                        <input type="text" name="nama_pembayaran" class="form-control" id="nama_pembayaran" placeholder="Tuliskan Nama Pembayaran" value="{{ old('nama_pembayaran') }}" autocomplete="off">
                        @error("nama_pembayaran") <div class="small text-danger">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-group">
                        Jenis Pembayaran
                        <select name="jenis_pembayaran" class="form-control" data-placeholder="Pilih Jenis Pembayaran">
                            <option value=""></option>
                            <option value="tangan" {{ (old('jenis_pembayaran') == 'tangan') ? 'selected' : '' }}>Kas Non Bank</option>
                            <option value="bank" {{ (old('jenis_pembayaran') == 'tangan') ? 'selected' : '' }}>Kas di Bank</option>
                        </select>
                        @error('jenis_pembayaran') <div class="small text-danger">{{ $message }}</div> @enderror
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
            <div class="card-header">Daftar Pembayaran</div>
            <div class="card-body">

                <form action="{{ route('payment.index') }}" method="get">
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

                @if (COUNT($data))
                <div class="table-responsive">
                    <table class="table table-sm table-hover">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>Jenis</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $row)
                            <tr>
                                <td>{{ $row->nama }}</td>
                                <td>{{ $row->tipe_kas }}</td>
                                <td class="text-right">
                                    @if (COUNT($row->listpay) < 1)
                                    <form action="{{ route('payment.destroy') }}" method="POST">
                                        @csrf @method('delete') <input type="hidden" name="x_code" value="{{ $row->id }}"">
                                    @endif
                                        <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modal{{ $row->id }}">
                                            <i class="icon-note"></i>
                                        </button>
                                    @if (COUNT($row->listpay) < 1)
                                        <button type="submit" class="btn btn-danger btn-sm">
                                            <i class="icon-trash"></i>
                                        </button>
                                    </form>
                                    @endif
                                </td>
                            </tr>

                            <div class="modal fade" id="modal{{ $row->id }}" tabindex="-1" aria-labelledby="modal{{ $row->id }}Label" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="modal{{ $row->id }}Label">Ubah Pembayaran</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <form action="{{ route('payment.update') }}" method="POST">
                                            @csrf @method('patch') <input type="hidden" name="x_code" value="{{ $row->id }}"">
                                            <div class="modal-body">
                                                <div class="form-group">
                                                    Nama Pembayaran
                                                    <input type="text" name="nama_pembayaran" class="form-control" value="{{ $row->nama }}" placeholder="Tuliskan Nama Pembayaran" autocomplete="off">
                                                </div>

                                                <div class="form-group">
                                                    Jenis Pembayaran
                                                    <select name="jenis_pembayaran" class="form-control" data-placeholder="Pilih Jenis Pembayaran">
                                                        <option value=""></option>
                                                        <option value="tangan" {{ ($row->status == 1) ? 'selected' : '' }}>Kas Non Bank</option>
                                                        <option value="bank" {{ ($row->status == 2) ? 'selected' : '' }}>Kas di Bank</option>
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
                        </tbody>
                    </table>
                </div>

                {{ $data->appends($_GET)->onEachSide(0)->links() }}
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

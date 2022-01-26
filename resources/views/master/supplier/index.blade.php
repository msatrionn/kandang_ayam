@extends('layouts.main')

@section('title', 'Daftar Supplier')

@section('footer')
<script>
$(".pagination").attr('class', 'pagination pagination-sm pt-2');
</script>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                Buat Supplier
            </div>
            <div class="card-body">

                <form action="{{ route('supplier.store') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        Nama Supplier
                        <input type="text" name="nama_supplier" class="form-control" id="nama_supplier" value="{{ old('nama_supplier') }}" placeholder="Tuliskan Nama Supplier" autocomplete="off">
                        @error("nama_supplier") <div class="small text-danger">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-group">
                        Nomor Telepon
                        <input type="number" name="nomor_telepon" class="form-control" id="nomor_telepon" value="{{ old('nomor_telepon') }}" placeholder="Tuliskan Nomor Telepon" autocomplete="off">
                        @error("nomor_telepon") <div class="small text-danger">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-group">
                        Alamat
                        <textarea name="alamat" id="alamat" class="form-control" placeholder="Tuliskan Alamat" rows="3">{{ old('alamat') }}</textarea>
                        @error("alamat") <div class="small text-danger">{{ $message }}</div> @enderror
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
            <div class="card-header">
                Daftar Supplier
            </div>
            <div class="card-body">
                <form action="{{ route('supplier.index') }}" method="get">
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
                @foreach ($data as $row)
                <div class="border rounded p-2 mb-2">
                    <div class="row">
                        <div class="col pr-1 text-bold">{{ $row->nama }}</div>
                        <div class="col-auto pl-1">{{ $row->telepon }}</div>
                    </div>
                    <div class="row">
                        <div class="col pr-1">{{ $row->alamat }}</div>
                        <div class="col-auto pl-1">
                            <form action="{{ route('supplier.detail') }}" class="d-inline-block mr-1" method="POST">
                                @csrf @method('put') <input type="hidden" name="x_code" value="{{ $row->id }}">
                                <button type="submit" class="btn btn-link p-0 text-danger">
                                    <i class="fa fa-file-pdf-o"></i>
                                </button>
                            </form>
                            @if (COUNT($row->relateproduk) < 1)
                            <form action="{{ route('supplier.destroy') }}" class="d-inline-block" method="POST">
                                @csrf @method('delete') <input type="hidden" name="x_code" value="{{ $row->id }}"">
                            @endif
                                <button type="button" class="btn btn-link p-0 text-primary" data-toggle="modal" data-target="#modal{{ $row->id }}">
                                    <i class="icon-note"></i>
                                </button>
                            @if (COUNT($row->relateproduk) < 1)
                                <button type="submit" class="btn btn-link p-0 text-danger">
                                    <i class="icon-trash"></i>
                                </button>
                            </form>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach

                <div class="modal fade" id="modal{{ $row->id }}" tabindex="-1" aria-labelledby="modal{{ $row->id }}Label" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="modal{{ $row->id }}Label">Ubah Supplier</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <form action="{{ route('supplier.update') }}" method="POST">
                                @csrf @method('patch') <input type="hidden" name="x_code" value="{{ $row->id }}"">
                                <div class="modal-body">
                                    <div class="form-group">
                                        Nama Supplier
                                        <input type="text" name="nama_supplier" class="form-control" value="{{ $row->nama }}" placeholder="Tuliskan Nama Supplier" autocomplete="off">
                                    </div>

                                    <div class="form-group">
                                        Nomor Telepon
                                        <input type="number" name="nomor_telepon" class="form-control" value="{{ $row->telepon }}" placeholder="Tuliskan Nomor Telepon" autocomplete="off">
                                    </div>

                                    <div class="form-group">
                                        Alamat
                                        <textarea name="alamat" class="form-control" placeholder="Tuliskan Alamat" rows="3">{{ $row->alamat }}</textarea>
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

                {{ $data->appends($_GET)->onEachSide(0)->links() }}
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

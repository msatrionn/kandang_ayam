@extends('layouts.main')

@section('title', 'Daftar Tipe')

@section('footer')
<script>
$(".pagination").attr('class', 'pagination pagination-sm pt-2');
</script>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">Tambah Tipe</div>
            <div class="card-body">
                <form action="{{ route('tipe.store') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        Nama Tipe
                        <input type="text" name="nama_tipe" class="form-control" id="nama_tipe" placeholder="Tuliskan Nama Tipe" value="{{ old('nama_tipe') }}" autocomplete="off">
                        @error("nama_tipe") <div class="small text-danger">{{ $message }}</div> @enderror
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
            <div class="card-header">Daftar Tipe</div>
            <div class="card-body">

                <form action="{{ route('tipe.index') }}" method="get">
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
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $row)
                            <tr>
                                <td>{{ $row->nama }}</td>
                                <td class="text-right">
                                    @if (COUNT($row->producttipe) < 1)
                                    <form action="{{ route('tipe.destroy') }}" method="POST">
                                        @csrf @method('delete') <input type="hidden" name="x_code" value="{{ $row->id }}"">
                                    @endif
                                        <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modal{{ $row->id }}">
                                            <i class="icon-note"></i>
                                        </button>
                                    @if (COUNT($row->producttipe) < 1)
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
                                            <h5 class="modal-title" id="modal{{ $row->id }}Label">Ubah Tipe</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <form action="{{ route('tipe.update') }}" method="POST">
                                            @csrf @method('patch') <input type="hidden" name="x_code" value="{{ $row->id }}"">
                                            <div class="modal-body">
                                                <div class="form-group">
                                                    Nama Tipe
                                                    <input type="text" name="nama_tipe" class="form-control" value="{{ $row->nama }}" placeholder="Tuliskan Nama Tipe" autocomplete="off">
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

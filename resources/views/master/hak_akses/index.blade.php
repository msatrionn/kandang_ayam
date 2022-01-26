@extends('layouts.main')

@section('title', 'Hak Akses')

@section('content')
<div class="row">
    <div class="col-md-5">
        <div class="card">
            <div class="card-header">Input User Baru</div>
            <div class="card-body">
                <form action="{{ route('hakakses.store') }}" method="post">
                    @csrf
                    <div class="form-group">
                        Nama User
                        <input type="text" name="nama_user" autocomplete="off" placeholder="Tulis Nama User" class="form-control" required>
                    </div>

                    <div class="form-group">
                        E-Mail
                        <input type="email" name="email_user" autocomplete="off" placeholder="Tulis Email User" class="form-control" required>
                    </div>

                    <div class="form-group">
                        Password
                        <input type="password" name="password" class="form-control" required>
                    </div>

                    <div class="form-group text-right">
                        <button class="btn btn-primary" type="submit">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-7">
        <div class="card">
            <div class="card-header">Data User</div>
            <div class="card-body">
                @foreach ($data as $row)
                <div class="border cursor rounded mb-2 p-2" data-toggle="modal" data-target="#static{{ $row->id }}">
                    <div class="row">
                        <div class="col pr-1">{{ $row->name }}</div>
                        <div class="col-auto pl-1">{{ $row->email }}</div>
                    </div>
                </div>

                <div class="modal fade" id="static{{ $row->id }}" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="static{{ $row->id }}Label" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="static{{ $row->id }}Label">Data User</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <form action="{{ route('hakakses.update') }}" method="post">
                                @csrf @method('patch') <input type="hidden" name="id" value="{{ $row->id }}">
                                <div class="modal-body">
                                    <div class="form-group">
                                        Nama User
                                        <input type="text" name="nama_user" value="{{ $row->name }}" autocomplete="off" placeholder="Tulis Nama User" class="form-control" required>
                                    </div>

                                    <div class="form-group">
                                        E-Mail
                                        <input type="email" name="email_user" value="{{ $row->email }}" autocomplete="off" placeholder="Tulis Email User" class="form-control" required>
                                    </div>

                                    <div class="form-group">
                                        Password
                                        <input type="password" name="password" class="form-control">
                                        <div class="small">*) Tulis password apabila akan diganti</div>
                                    </div>

                                    <hr>

                                    @php
                                        $ext    =   explode(',', $row->permission ?? '');
                                    @endphp
                                    @foreach ($akses as $item)
                                    <table class="table table-sm m-0">
                                        <tbody>
                                            <tr>
                                                <td>{{ $item->nama }}</td>
                                                <td class="text-right">
                                                    <input value="{{ $item->id }}" type="checkbox" name="chk[]" @for ($x=0;$x<COUNT($ext);$x++)
                                                    @if ($item->id == $ext[$x]) {{ 'checked' }}@endif @endfor>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    @endforeach
                                </div>

                                <div class="p-3">
                                    <button type="submit" class="btn btn-primary float-right">Ubah User</button>
                                    <button type="button" class="btn btn-secondary float-right mr-2" data-dismiss="modal">Close</button>
                                </form>
                                <form action="{{ route('hakakses.destroy') }}" method="post">
                                    @csrf @method('delete') <input type="hidden" name="id" value="{{ $row->id }}">
                                    <button type="submit" class="btn btn-danger">Hapus User</button>
                                </form>
                                </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection

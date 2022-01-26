@extends('layouts.main')

@section('title', 'Daftar Kandang')

@section('content')
<div class="row">
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">Tambah Kandang</div>
            <div class="card-body">
                <form action="{{ route('kandang.store') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        Nama Kandang
                        <input type="text" name="nama_kandang" class="form-control" id="nama_kandang" placeholder="Tuliskan Nama Kandang" value="{{ old('nama_kandang') }}" autocomplete="off">
                        @error("nama_kandang") <div class="small text-danger">{{ $message }}</div> @enderror
                    </div>

                    {{-- <div class="row">
                        <div class="col pr-1">
                            <div class="form-group">
                                Bangunan
                                <input type="text" name="bangunan" class="form-control" id="bangunan" placeholder="Tuliskan Bangunan" value="{{ old('bangunan') }}" autocomplete="off">
                                @error("bangunan") <div class="small text-danger">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="col pl-1">
                            <div class="form-group">
                                Kode
                                <input type="text" name="kode" class="form-control" id="kode" placeholder="Tuliskan Kode" value="{{ old('kode') }}" autocomplete="off">
                                @error("kode") <div class="small text-danger">{{ $message }}</div> @enderror
                            </div>
                        </div>
                    </div>


                    <div class="form-group">
                        Jumlah Box
                        <input type="numeric" name="jumlah_box" class="form-control" id="jumlah_box" placeholder="Tuliskan Jumlah Box" value="{{ old('jumlah_box') }}" autocomplete="off">
                        @error("jumlah_box") <div class="small text-danger">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-group">
                        Ekor Per Box
                        <input type="numeric" name="ekor_per_box" class="form-control" id="ekor_per_box" placeholder="Tuliskan Jumlah Ekor Per Box" value="{{ old('ekor_per_box') }}" autocomplete="off">
                        @error("ekor_per_box") <div class="small text-danger">{{ $message }}</div> @enderror
                    </div> --}}

                    <div class="form-group text-right">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">Daftar Kandang</div>
            <div class="card-body">

                <form action="{{ route('kandang.index') }}" method="get">
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
                <div class="accordion" id="accordionKandang">
                    @foreach ($data as $row)
                    @php
                        $exp    =   json_decode($row->json_data) ;
                    @endphp

                    <div class="card-header" id="heading{{ $row->id }}">
                        <div class="p-2 cursor" data-toggle="collapse" data-target="#collapse{{ $row->id }}" aria-expanded="true" aria-controls="collapse{{ $row->id }}">
                            {{ $row->nama }}
                        </div>
                    </div>

                    <div id="collapse{{ $row->id }}" class="collapse @if (session('id')) {{ session('id') == $row->id ? 'show' : '' }} @endif" aria-labelledby="heading{{ $row->id }}" data-parent="#accordionKandang">
                        <div class="p-2">
                            <form action="{{ route('kandang.update') }}" method="POST">
                                @csrf @method('patch') <input type="hidden" name="x_code" value="{{ $row->id }}"">
                                <div class="row">
                                    <div class="col pr-1">
                                        <div class="form-group">
                                            Nama Kandang
                                            <input type="text" name="nama_kandang" class="form-control" value="{{ $row->nama }}" placeholder="Tuliskan Nama Kandang" autocomplete="off">
                                        </div>
                                    </div>
                                    <div class="col-5 pl-1">
                                        <div class="form-group">
                                            &nbsp;
                                            <div class="row">
                                                <div class="col-8 pr-1">
                                                    <button type="submit" class="px-1 btn btn-primary btn-block">Save</button>
                                                </div>
                            </form>
                            <div class="col-4 pl-1">
                            <form action="{{ route('kandang.destroy') }}" method="post">
                                @csrf @method('delete')  <input type="hidden" name="x_code" value="{{ $row->id }}""> <input type="hidden" name="type" value="master">
                                    <button type="submit" class="px-1 btn btn-danger btn-block"><i class="fa fa-trash"></i></button>
                                </form>
                            </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            <form action="{{ route('kandang.bangunan') }}" method="post">
                                @csrf @method('put') <input type="hidden" name="x_code" value="{{ $row->id }}"">
                                <div class="border p-2">
                                    <div class="row">
                                        <div class="col pr-1">
                                            <div class="form-group">
                                                Bangunan
                                                <input type="text" name="bangunan" class="form-control" placeholder="Tuliskan Bangunan" autocomplete="off">
                                            </div>
                                        </div>

                                        <div class="col pl-1">
                                            <div class="form-group">
                                                Kode
                                                <input type="text" name="kode" class="form-control" placeholder="Tuliskan Kode" autocomplete="off">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col pr-1">
                                            <div class="form-group">
                                                Jumlah Box
                                                <input type="numeric" name="jumlah_box" class="form-control" placeholder="Tuliskan Jumlah Box" autocomplete="off">
                                            </div>
                                        </div>

                                        <div class="col pl-1">
                                            <div class="form-group">
                                                Ekor Per Box
                                                <input type="numeric" name="ekor_per_box" class="form-control" placeholder="Tuliskan Jumlah Ekor Per Box" autocomplete="off">
                                            </div>
                                        </div>
                                    </div>

                                    <button class="btn btn-primary btn-block">Submit</button>
                                </div>
                            </form>

                            <div class="mt-2">
                                @if ($row->json_data)
                                    @foreach ($exp as $item)
                                    <div class="border p-2 mb-2 rounded">
                                        <div class="row">
                                            <div class="col pr-1">Bangunan {{ $item->bangunan }} | Kode {{ $item->kode }} | Jumlah Ekor : {{ number_format($item->jumlah_box * $item->ekor_per_box) }} Ekor<br>
                                                Jumlah Box : {{ number_format($item->jumlah_box) }} | Ekor per Box : {{ number_format($item->ekor_per_box) }}</div>
                                            <div class="col-auto text-right pl-1">
                                                <form action="{{ route('kandang.destroy') }}" method="post">
                                                    @csrf @method('delete')  <input type="hidden" name="x_code" value="{{ $row->id }}">
                                                    <input type="hidden" name="bg" value="{{ $item->id }}">
                                                    <input type="hidden" name="type" value="bangunan">
                                                    <button type="submit" class="btn btn-sm btn-danger btn-block"><i class="fa fa-trash"></i></button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>

                    </div>
                    @endforeach
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@extends('layouts.main')

@section('title', 'Daftar Stock')

@section('footer')
<script>
$(".pagination").attr('class', 'pagination pagination-sm pt-2');
</script>
@endsection

@section('content')
<div class="card">
    <div class="card-header">Daftar Stok Barang Tersedia</div>
    <div class="card-body">

        <form action="{{ route('stock.index') }}" method="get">
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

        <div class="row">
            @foreach ($data as $row)
            <div class="col-lg-6">
                <div class="border rounded p-2 mb-2">
                    <div class="row">
                        <div class="col pr-1">
                            <span class="small text-primary text-bold border-right pr-1 mr-1">{{ $row->tipeset->nama }}</span>{{ $row->produk->nama }}
                        </div>
                        <div class="col-auto pl-1">{{ Tanggal::date($row->delivery->tanggal) }}</div>
                    </div>
                    <div class="border-top mt-1 pt-1 small">
                        <label>Datang : {{ number_format($row->qty_awal) . ' ' . $row->produk->tipesatuan->nama }} | Sisa : {{ number_format($row->stock_opname) . ' ' . $row->produk->tipesatuan->nama }}</label>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        {{ $data->appends($_GET)->onEachSide(0)->links() }}
    </div>
</div>
@endsection

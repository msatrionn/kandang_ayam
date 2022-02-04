@extends('layouts.main')
@section('header')
<link rel="stylesheet" href="{{ asset('js/vendor/politespace/politespace.css') }}">
<link rel="stylesheet" href="{{ asset('assets/vendor/select2/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/vendor/select2/select2-bootstrap4.min.css') }}">
@endsection

@section('footer')
<script src="{{ asset('js/accounting.js') }}"></script>
<script src="{{ asset('js/vendor/politespace/libs/libs.js') }}"></script>
<script src="{{ asset('js/vendor/politespace/politespace.js') }}"></script>
<script src="{{ asset('js/vendor/politespace/politespace-init.js') }}"></script>
<script src="{{ asset('assets/vendor/select2/select2.js') }}"></script>
<script>
    $(".labarugi").load("{{ route('labarugi.table') }}")
</script>
<script>
    function handleChange() {
        console.log('cek');
        var awal=$("[name=awal]").val()
        var akhir=$("[name=akhir]").val()
        var angkatan=$("[name=angkatan]").val()
        if (angkatan) {
            console.log('angk');
            $("[name=awal]").val("")
            $("[name=akhir]").val("")
        }
        if (awal || akhir) {
            $("[name=angkatan]").val("")
        }
        console.log(angkatan);
        $.ajax({
            url:"{{ route('labarugi.table') }}",
            method:"GET",
            data:{
                awal:awal,
                akhir:akhir,
                angkatan:angkatan
            },
            success:function(data){
                $(".labarugi").html(data)

            }
        })
    }
</script>
@endsection

@section('title', 'Report Laba Rugi')

@section('content')

<div class="card">
    <div class="card-body">
        <form action="" method="get">
            <div class="row mb-4 py-4">
                <div class="col-md-3">
                    <label for="">Tanggal Awal</label>
                    <input type="date" name="awal" id="" class="form-control" onchange="handleChange()">
                </div>
                <div class="col-md-3">
                    <label for="">Tanggal Akhir</label>
                    <input type="date" name="akhir" id="" class="form-control" onchange="handleChange()">
                </div>
                <div class="col-md-3">
                    {{-- <div class="angkatan"></div> --}}
                    <label for="">Angkatan</label>
                    <select name="angkatan" class="form-control select2" onchange="handleChange()">
                        <option value="" aria-readonly="true">Pilih angkatan</option>
                        @php
                        $angkatan = Riwayat::select('angkatan')->distinct()->orderBy('angkatan',
                        'asc')->get();
                        @endphp
                        @foreach ($angkatan as $item)
                        <option value="{{ $item->angkatan }}">{{ $item->angkatan }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </form>
        <div class="labarugi"></div>
    </div>
</div>


@endsection

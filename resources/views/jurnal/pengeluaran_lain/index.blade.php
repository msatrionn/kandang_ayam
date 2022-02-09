@extends('layouts.main')

@section('header')
<link rel="stylesheet" href="{{ asset('js/vendor/politespace/politespace.css') }}">
<link rel="stylesheet" href="{{ asset('assets/vendor/select2/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/vendor/select2/select2-bootstrap4.min.css') }}">
@endsection

@section('footer')
<script>
    $(".pagination").attr('class', 'pagination pagination-sm pt-2');
</script>
<script src="{{ asset('js/vendor/politespace/libs/libs.js') }}"></script>
<script src="{{ asset('js/vendor/politespace/politespace.js') }}"></script>
<script src="{{ asset('js/vendor/politespace/politespace-init.js') }}"></script>

<script src="{{ asset('assets/vendor/select2/select2.js') }}"></script>

<script>
    $(document).ready(function() {
        $(document).on('click', '#check_kas', function() {
            var metode_select       =   $("#check_kas") ;
            var input               =   document.getElementById('metode_select');
            var metode              =   document.getElementById('select_kas');
            var tulis_kas           =   document.getElementById('tulis_kas');

            if (metode_select.prop('checked')){
                input.style         =   'display: none' ;
                tulis_kas.style     =   'display: block' ;
                tulis_kas.value     =   '';
                $("#select_kas").val("").trigger("change");
            } else {
                input.style         =   'display: block' ;
                tulis_kas.style     =   'display: none' ;
                tulis_kas.value     =   '';
                $("#select_kas").val("").trigger("change");
            }
        });
    });
</script>
<script>
    var count=0;
    $(".add_class").on('click',function (e) {
        e.preventDefault();
        count += 1
        console.log(count);
        $("#add").append(`
        <div class="remove_add_${count}">
            <div class="col-auto pl-1 text-right">
                <i class="fa fa-trash cursor text-danger pt-2 mt-1" onClick="removeRow(${count})"></i>
            </div>
            <div class="form-group">
                Jenis
                <select name="jenis[]" data-placeholder="Pilih Jenis" class="form-control select2_${count}">
                    <option value="">Pilih jenis</option>
                    <option value="transport">Transport</option>
                    <option value="sewa_kandang">Sewa Kandang</option>
                    <option value="operasional">Operasional</option>
                    <option value="humas">Humas</option>
                </select>
            </div>
            <div class="form-group">
                Nominal Pengeluaran
                <input type="number" name="nominal_pengeluaran[]" class="form-control" value="{{ old('nominal_pengeluaran') }}"
                    id="nominal_pengeluaran" placeholder="Tuliskan Nominal Pengeluaran" autocomplete="off" data-politespace
                    data-politespace-grouplength="3" data-politespace-delimiter="," data-politespace-decimal-mark="."
                    step="0.01" data-politespace-reverse required>
                @error('nominal_pengeluaran') <div class="small text-danger">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                Keterangan
                <textarea name="keterangan[]" required id="keterangan" class="form-control" placeholder="Tuliskan Keterangan"
                    rows="3">{{ old('keterangan') }}</textarea>
                @error('keterangan') <div class="small text-danger">{{ $message }}</div> @enderror
            </div>
        </div>`)
    })
    function removeRow(count_id) {
        // console.log($(`.remove_add_${count_id}`).attr('class'));
        $(`.remove_add_${count_id}`).remove()
    }
</script>

<script>
    $(".riwayat").load("{{ route('keluarlain.riwayat') }}")

</script>
<script>
    $("[name=cari]").on('keyup',function () {
    console.log('cek');
    $.ajax({
    url:"{{ route('keluarlain.riwayat') }}",
    method:"GET",
    data:{
    cari:$(this).val(),
    key:"tgl",
    tgl:$("[name=tgl]").val(),
    },
    success:function(data){
    $(".riwayat").html(data)
    }
    })
    })
</script>
<script>
    $("[name=cari_produk]").on('keyup',function () {
    console.log('cek');
    $.ajax({
    url:"{{ route('keluarlain.riwayat') }}",
    method:"GET",
    data:{
    cari_produk:$(this).val(),
    key:"produk",
    tgl:$("[name=tgl]").val(),
    },
    success:function(data){
    $(".riwayat").html(data)
    }
    })
    })
</script>
<script>
    $("[name=angkatan]").on('change',function () {
            $.ajax({
                url:"{{ route('purchasing.index',['key'=>'kandang']) }}",
                method:"GET",
                data:{
                    angkatan_id:$(this).val()
                },
                success:function (data) {
                    console.log(data);
                    $("#kandang-select").html(data)
                }
            })
        })
</script>
@endsection

@section('title', 'Pengeluaran Lain')

@section('content')
<div class="row">
    <div class="col-md-5">
        <div class="card">
            <div class="card-header">Form Pengeluaran</div>
            <form action="{{ route('keluarlain.store') }}" method="post">
                @csrf
                <div class="card-body">
                    <div class="form-group">
                        Angkatan
                        <select name="angkatan" id="angkatan" class="form-control select2" data-width="100%"
                            data-placeholder="Pilih angkatan">
                            <option value=""></option>
                            <option value="ALL">Tanpa Angkatan </option>
                            @foreach ($angkatan as $id => $row)
                            <option value="{{ $id }}">{{ $row }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div id="kandang-select">
                        <div class="form-group">
                            Kandang
                            <select name="kandang" id="kandang" class="form-control select2"
                                data-placeholder="Pilih Kandang" data-width="100%">
                                <option value=""></option>
                                <option value="ALL">Tanpa Kandang</option>
                                @foreach ($kandang as $id => $row)
                                <option value="{{ $id }}">{{ $row }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        Tanggal
                        <input type="date" name="tanggal" class="form-control" value="{{ old('tanggal') }}" id="tanggal"
                            placeholder="Tuliskan Tanggal" autocomplete="off">
                        @error('tanggal') <div class="small text-danger">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-group">
                        Kas
                        <div id="metode_select">
                            <select name="select_kas" id='select_kas' data-placeholder="Pilih Kas" class="form-control">
                                <option value=""></option>
                                @foreach ($payment as $id => $row)
                                <option value="{{ $id }}" {{ old('select_kas')==$id ? 'selected' : '' }}>{{ $row }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <input type="text" style="{{ old('check_kas') ? '' : 'display: none' }}" name="tulis_kas"
                            id="tulis_kas" value="{{ old('tulis_kas') }}" placeholder="Tulis Kas" autocomplete="off"
                            class="form-control">
                        <label class="mt-2"><input id="check_kas" name="check_kas" {{ old('check_kas') ? 'checked' : ''
                                }} type="checkbox"> Input kas manual / Tidak ada di list</label>
                        @error('select_kas') <div class="small text-danger">{{ $message }}</div> @enderror
                        @error('tulis_kas') <div class="small text-danger">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-auto pl-1 text-right">
                        <i class="fa fa-plus cursor text-success pt-2 mt-1 add_class"></i>
                    </div>
                    <div class="form-group">
                        Jenis
                        <select name="jenis[]" id='jenis' data-placeholder="Pilih Jenis" class="form-control">
                            <option value="">Pilih jenis</option>
                            <option value="transport">Transport</option>
                            <option value="sewa_kandang">Sewa Kandang</option>
                            <option value="operasional">Operasional</option>
                            <option value="humas">Humas</option>
                        </select>
                    </div>
                    <div class="form-group">
                        Nominal Pengeluaran
                        <input type="number" name="nominal_pengeluaran[]" class="form-control"
                            value="{{ old('nominal_pengeluaran') }}" id="nominal_pengeluaran"
                            placeholder="Tuliskan Nominal Pengeluaran" autocomplete="off" data-politespace
                            data-politespace-grouplength="3" data-politespace-delimiter=","
                            data-politespace-decimal-mark="." step="0.01" data-politespace-reverse>
                        @error('nominal_pengeluaran') <div class="small text-danger">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-group">
                        Keterangan
                        <textarea name="keterangan[]" id="keterangan" class="form-control"
                            placeholder="Tuliskan Keterangan" rows="3">{{ old('keterangan') }}</textarea>
                        @error('keterangan') <div class="small text-danger">{{ $message }}</div> @enderror
                    </div>
                    <div id="add"></div>
                </div>

                <div class="card-footer text-right">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>

    <div class="col-md-7">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('keluarlain.index') }}" method="get">
                    <input type="hidden" name="key" value="unduh">
                    <div class="row">
                        <div class="col-lg col-6"><input type="date" name="mulai" class="form-control"></div>
                        <div class="col-lg col-6"><input type="date" name="selesai" class="form-control"></div>
                        <div class="col-xl-auto col-12 pl-xl-1 mt-3 mt-xl-0">
                            <button type="submit" class="btn btn-outline-success btn-block"><i
                                    class="fa fa-file-excel-o"></i> Unduh</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="card">
            <div class="card-header">Riwayat Pengeluaran</div>
            <div class="form-group">
                <div class="row mt-4 ml-2 mr-2">
                    <div class="col-md-6">
                        <input type="text" name="cari" class="form-control cari" placeholder="Tanggal">
                    </div>
                    <div class="col-md-6">
                        <input type="text" name="cari_produk" class="form-control cari" placeholder="Produk">
                    </div>
                </div>
            </div>
            <div class="riwayat"></div>
        </div>
    </div>
</div>
</div>
@endsection

@extends('layouts.main')

@section('title', 'Mutasi')

@section('header')
<link rel="stylesheet" href="{{ asset('assets/vendor/select2/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/vendor/select2/select2-bootstrap4.min.css') }}">
@endsection

@section('footer')
<script src="{{ asset('assets/vendor/select2/select2.js') }}"></script>
<script>
    $("#data_produk").load("{{ route('mutasi.index', ['key' => 'produk']) }}") ;
    $("#data_ayam").load("{{ route('mutasi.index', ['key' => 'ayam']) }}") ;
    $("#data_mutasi").load("{{ route('mutasi.index', ['key' => 'mutasi']) }}") ;
    $("#riwayat").load("{{ route('mutasi.index', ['key' => 'riwayat']) }}") ;
</script>

<script>
    $(document).ready(function() {

        $(document).on('click', '#mutasi_stock', function(e) {
            e.preventDefault();
            $(document).find("div.text-danger").remove();
            var produk          =   $("#produk").val();
            var qty             =   $("#qty").val();
            var kandang         =   $("#kandang").val();
            var tanggal_mutasi  =   $("#tanggal_mutasi").val();
            // var strain          =   $("#strain").val();

            if (qty < 1) {
                document.getElementById('notif-error').innerHTML  =   "Qty tidak boleh kosong" ;
                document.getElementById('notif-error').style      =   '';
                $('#topbar-notification').fadeIn();
                setTimeout(function() {
                    $('#topbar-notification').fadeOut();
                    document.getElementById('notif-error').style    =   'display: none';
                    document.getElementById('notif-success').style  =   'display: none';
                }, 3000) ;
            } else {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    url: "{{ route('mutasi.store') }}",
                    method: "POST",
                    data: {
                        produk          :   produk,
                        qty             :   qty,
                        kandang         :   kandang,
                        tanggal_mutasi  :   tanggal_mutasi,
                        // strain          :   strain,
                    },
                    success: function(data) {
                        console.log(data);
                        if (data.status == 400) {
                            document.getElementById('notif-error').innerHTML  =   data.msg;
                            document.getElementById('notif-error').style      =   '';
                            $('#topbar-notification').fadeIn();
                            setTimeout(function() {
                                $('#topbar-notification').fadeOut();
                                document.getElementById('notif-error').style    =   'display: none';
                                document.getElementById('notif-success').style  =   'display: none';
                            }, 3000) ;
                        } else {
                            $("#data_produk").load("{{ route('mutasi.index', ['key' => 'produk']) }}") ;
                            $("#data_ayam").load("{{ route('mutasi.index', ['key' => 'ayam']) }}") ;
                            $("#data_mutasi").load("{{ route('mutasi.index', ['key' => 'mutasi']) }}") ;
                            $("#riwayat").load("{{ route('mutasi.index', ['key' => 'riwayat']) }}") ;
                            document.getElementById('notif-success').innerHTML  =   'Mutasi Berhasil Dilakukan';
                            document.getElementById('notif-success').style      =   '';
                            $('#topbar-notification').fadeIn();
                            setTimeout(function() {
                                $('#topbar-notification').fadeOut();
                                document.getElementById('notif-error').style    =   'display: none';
                                document.getElementById('notif-success').style  =   'display: none';
                            }, 3000) ;
                        }
                    }
                });
            }
        });
    });
</script>
<script>
    $("#cari").on("keyup",function (e) {
        e.preventDefault()
        $.ajax({
            url:"{{ route('mutasi.search') }}",
            method:"GET",
            data:{
                cari:$(this).val(),
                key:'produk'
            },
            success:function (data) {
                $("#data_ayam").html(data)
                $("#data_produk").hide()
            }
        })
    })
</script>
<script>
    $("#cari_riwayat").on("keyup",function (e) {
        e.preventDefault()
        $.ajax({
            url:"{{ route('mutasi.search') }}",
            method:"GET",
            data:{
                cari:$(this).val(),
                key:'riwayat'

            },
            success:function (data) {
                $("#riwayat").html(data)
            }
        })
    })
</script>
@endsection

@section('content')
<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">Daftar Stock</div>
            <div class="card-body">
                <input type="text" name="cari" id="cari" class="form-control mt-4 mb-4" placeholder="Cari">
                <div id="data_ayam"></div>
                <div id="data_produk"></div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">Data Mutasi</div>
            <div class="card-body">
                <div id="data_mutasi"></div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <form action="{{ route('mutasi.index') }}" method="get">
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
        <input type="text" name="cari_riwayat" id="cari_riwayat" class="form-control mt-4 mb-4" placeholder="Cari">
        <div id="riwayat"></div>
    </div>
</div>
@endsection

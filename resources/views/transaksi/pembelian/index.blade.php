@extends('layouts.main')

@section('title', 'Jurnal Pembelian')

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
    $("#form_pembelian").load("{{ route('pembelian.index', ['key' => 'input']) }}");
$("#daftar_pembelian").load("{{ route('pembelian.index', ['key' => 'daftar']) }}");
</script>

<script>
    $(document).on('click', '#input_pembelian', function() {
        var angkatan            =   $("[name=angkatan]").val() ;
        var kandang             =   $("#kandang").val() ;
        console.log(angkatan);
        console.log(kandang);
        var produk              =   $("#produk").val() ;
        var check_produk        =   $("#check_produk:checked").val() ;
        var tulis_produk        =   $("#tulis_produk").val() ;

        var satuan              =   $("#satuan").val() ;
        var check_satuan        =   $("#check_satuan:checked").val() ;
        var tulis_satuan        =   $("#tulis_satuan").val() ;

        var jumlah_beli         =   $("#jumlah_beli").val() ;
        var harga_pembelian     =   $("#harga_pembelian").val() ;

        var metode_pembayaran   =   $("#metode_pembayaran").val() ;
        var check_kas           =   $("#check_kas:checked").val() ;
        var tulis_pembayaran    =   $("#tulis_pembayaran").val() ;

        var tanggal             =   $("#tanggal").val() ;

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            url: "{{ route('pembelian.store') }}",
            method: "POST",
            data: {
                kandang             :   kandang,
                angkatan            :   angkatan,
                produk              :   produk,
                check_produk        :   check_produk,
                tulis_produk        :   tulis_produk,
                satuan              :   satuan,
                check_satuan        :   check_satuan,
                tulis_satuan        :   tulis_satuan,
                jumlah_beli         :   jumlah_beli,
                harga_pembelian     :   harga_pembelian,
                metode_pembayaran   :   metode_pembayaran,
                check_kas           :   check_kas,
                tulis_pembayaran    :   tulis_pembayaran,
                tanggal             :   tanggal,
            },
            success: function(data) {
                if (data.status == 400) {
                    document.getElementById('notif-error').innerHTML  =   data.message;
                    document.getElementById('notif-error').style      =   '';
                    $('#topbar-notification').fadeIn();
                    setTimeout(function() {
                        $('#topbar-notification').fadeOut();
                        document.getElementById('notif-error').style    =   'display: none';
                        document.getElementById('notif-success').style  =   'display: none';
                    }, 3000) ;
                } else {
                    $("#form_pembelian").load("{{ route('pembelian.index', ['key' => 'input']) }}");
                    $("#daftar_pembelian").load("{{ route('pembelian.index', ['key' => 'daftar']) }}");

                    document.getElementById('notif-success').innerHTML  =   "Jurnal Pembelian berhasil";
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
    });
</script>

<script>
    var url =   "{{ route('pembelian.index', ['key' => 'daftar']) }}";
$('#search').on('keyup', function(){
    $.ajax({
        url: url + "&search=" +$(this).val(),
        method: "GET",
        success: function(response) {
            $('#daftar_pembelian').html(response);
        }

    });
})
</script>
@endsection

@section('content')
<div class="row">
    <div class="col-md-5">
        <div class="card">
            <div class="card-header">Form Pembelian</div>
            <div class="card-body">
                <div id="form_pembelian"></div>
            </div>
        </div>
    </div>

    <div class="col-md-7">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('pembelian.index') }}" method="get">
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
            <div class="card-header">Riwayat Pembelian</div>
            <div class="card-body">
                <div class="form-group">
                    <input type="text" name="search" id="search" autocomplete="off" placeholder="Pencarian..."
                        class="form-control search-data">
                </div>

                <div id="daftar_pembelian"></div>
            </div>
        </div>
    </div>
</div>
@endsection

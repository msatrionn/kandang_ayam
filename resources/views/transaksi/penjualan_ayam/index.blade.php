@extends('layouts.main')

@section('title', 'Jurnal Penjualan Ayam')

@section('header')
<link rel="stylesheet" href="{{ asset('js/vendor/politespace/politespace.css') }}">
<link rel="stylesheet" href="{{ asset('assets/vendor/select2/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/vendor/select2/select2-bootstrap4.min.css') }}">
@endsection

@section('footer')
<script src="{{ asset('js/vendor/politespace/libs/libs.js') }}"></script>
<script src="{{ asset('js/vendor/politespace/politespace.js') }}"></script>
<script src="{{ asset('js/vendor/politespace/politespace-init.js') }}"></script>
<script src="{{ asset('assets/vendor/select2/select2.js') }}"></script>
<script>
    $("#stock_kandang").load("{{ route('penjualan.index', ['key' => 'stock']) }}");
    $("#list_trans").load("{{ route('penjualan.index', ['key' => 'list_trans']) }}");
    $("#input_trans").load("{{ route('penjualan.index', ['key' => 'input_trans']) }}");
    $("#daftar_trans").load("{{ route('penjualan.index', ['key' => 'daftar_trans']) }}");
</script>

<script>
    $(document).on('click', '.input_jual', function() {
    var id          =   $(this).data('id') ;
    var jual_ayam   =   $("#jual_ayam" + id).val() ;
    var harga_ayam  =   $("#harga_ayam" + id).val() ;
    var kandang     =   $("[name=kandang]").val() ;
    var angkatan    =   $("[name=angkatan]").val() ;
    var strain      =   $("[name=strain]").val() ;

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $.ajax({
        url: "{{ route('penjualan.store') }}",
        method: "POST",
        data: {
            id          :   id,
            jual_ayam   :   jual_ayam,
            harga_ayam  :   harga_ayam,
            kandang     :   kandang,
            angkatan    :   angkatan,
            strain      :   strain,
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
                $("#stock_kandang").load("{{ route('penjualan.index', ['key' => 'stock']) }}");
                $("#list_trans").load("{{ route('penjualan.index', ['key' => 'list_trans']) }}");

                document.getElementById('notif-success').innerHTML  =   "Ambil ayam berhasil";
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
    $(document).on('click', '.hapus_list', function() {
    var id          =   $(this).data('id') ;

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $.ajax({
        url: "{{ route('penjualan.destroy') }}",
        method: "DELETE",
        data: {
            id  :   id,
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
                $("#stock_kandang").load("{{ route('penjualan.index', ['key' => 'stock']) }}");
                $("#list_trans").load("{{ route('penjualan.index', ['key' => 'list_trans']) }}");

                document.getElementById('notif-success').innerHTML  =   "Pembatalan berhasil";
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
    $(document).on('click', '#selesaikan', function() {
    var konsumen            =   $("#konsumen").val() ;
    var perubahan           =   $("#perubahan").val() ;
    var tanggal             =   $("#tanggal").val() ;
    var nominal_bayar       =   $("#nominal_bayar").val() ;
    var metode_pembayaran   =   $("#metode_pembayaran").val() ;
    var tulis_pembayaran    =   $("#tulis_pembayaran").val() ;
    var check_kas           =   $("#check_kas:checked").val() ;

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $.ajax({
        url: "{{ route('penjualan.update') }}",
        method: "PATCH",
        data: {
            konsumen            :   konsumen,
            perubahan           :   perubahan,
            tanggal             :   tanggal,
            nominal_bayar       :   nominal_bayar,
            metode_pembayaran   :   metode_pembayaran,
            tulis_pembayaran    :   tulis_pembayaran,
            check_kas           :   check_kas,
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
                $("#stock_kandang").load("{{ route('penjualan.index', ['key' => 'stock']) }}");
                $("#list_trans").load("{{ route('penjualan.index', ['key' => 'list_trans']) }}");
                $("#input_trans").load("{{ route('penjualan.index', ['key' => 'input_trans']) }}");
                $("#daftar_trans").load("{{ route('penjualan.index', ['key' => 'daftar_trans']) }}");

                document.getElementById('notif-success').innerHTML  =   "Transaksi berhasil diselesaikan";
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
    $(document).on('click', '.hapus_trans', function() {
    var id  =   $(this).data('id') ;

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $.ajax({
        url: "{{ route('penjualan.hapus') }}",
        method: "PUT",
        data: {
            id  :   id,
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
                $("#stock_kandang").load("{{ route('penjualan.index', ['key' => 'stock']) }}");
                $("#input_trans").load("{{ route('penjualan.index', ['key' => 'input_trans']) }}");
                $("#daftar_trans").load("{{ route('penjualan.index', ['key' => 'daftar_trans']) }}");

                document.getElementById('notif-success').innerHTML  =   "Hapus transaksi berhasil. Anda dapat melakukan perubahan transaksi sesuai transaksi dihapus";
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
    $(document).ready(function() {
        $(document).on('click', '#check_kas', function() {
            var metode_select       =   $("#check_kas") ;
            var input               =   document.getElementById('metode_select');
            var metode              =   document.getElementById('metode_pembayaran');
            var tulis_kas           =   document.getElementById('tulis_pembayaran');

            if (metode_select.prop('checked')){
                input.style         =   'display: none' ;
                tulis_kas.style     =   'display: block' ;
                tulis_kas.value     =   '';
                $("#metode_pembayaran").val("").trigger("change");
            } else {
                input.style         =   'display: block' ;
                tulis_kas.style     =   'display: none' ;
                tulis_kas.value     =   '';
                $("#metode_pembayaran").val("").trigger("change");
            }
        });
    });
</script>
@endsection

@section('content')
<div class="row">
    <div class="col-md-5">
        <div class="card">
            <div class="card-header">
                Stock Ayam di Kandang
            </div>
            <div class="card-body">
                <div id="stock_kandang"></div>
            </div>
        </div>
    </div>

    <div class="col-md-7">
        <div class="card">
            <div class="card-body">
                <div id="list_trans"></div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div id="input_trans"></div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">Transaksi Terakhir</div>
            <div class="card-body">
                <div id="daftar_trans"></div>
            </div>
        </div>
    </div>
</div>
@endsection

@extends('layouts.main')

@section('title', 'Jurnal Penggajian')

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
    $("#form_input").load("{{ route('gaji.index', ['key' => 'input']) }}") ;
$("#riwayat_gaji").load("{{ route('gaji.index', ['key' => 'riwayat_gaji']) }}") ;
</script>

<script>
    $(document).ready(function() {
    $(document).on('click', '#selesaikan', function() {
        var tanggal             =   $("#tanggal").val() ;
        var nama_karyawan       =   $("#nama_karyawan").val() ;
        var check_karyawan      =   $("#check_karyawan:checked").val() ;
        var tulis_karyawan      =   $("#tulis_karyawan").val() ;
        var nomor_telepon       =   $("#nomor_telepon").val() ;
        var alamat              =   $("#alamat").val() ;
        var tanggal_masuk       =   $("#tanggal_masuk").val() ;
        var gaji_per_hari       =   $("#gaji_per_hari").val() ;

        var metode_gaji         =   $("input:radio[name=metode_gaji]:checked").val() ;
        var gajibulan           =   $("#gajibulan").val() ;
        var harikerja           =   $("#harikerja").val() ;
        var gajihari            =   $("#gajihari").val() ;

        var lembur              =   $("#lembur:checked").val() ;
        var metode_lembur       =   $("input:radio[name=metode_lembur]:checked").val() ;
        var jamover             =   $("#jamover").val() ;
        var overdanajam         =   $("#overdanajam").val() ;
        var hariover            =   $("#hariover").val() ;
        var overhari            =   $("#overhari").val() ;

        var potongan            =   $("#potongan:checked").val() ;
        var potong_gaji         =   $("#potong_gaji").val() ;

        var thr                 =   $("#thr:checked").val() ;
        var thr_gaji            =   $("#thr_gaji").val() ;

        var bonus                 = $("#bonus:checked").val() ;
        var bonus_gaji            = $("#bonus_gaji").val() ;
        var keterangan            = $("#keterangan").val() ;

        var cashbon             =   $("#cashbon:checked").val() ;
        var nominal_cashbon     =   $("#nominal_cashbon").val() ;

        var metode_pembayaran   =   $("#metode_pembayaran").val() ;
        var check_kas           =   $("#check_kas:checked").val() ;
        var tulis_pembayaran    =   $("#tulis_pembayaran").val() ;

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            url: "{{ route('gaji.store') }}",
            method: "POST",
            data: {
                tanggal             :   tanggal ,
                nama_karyawan       :   nama_karyawan ,
                check_karyawan      :   check_karyawan ,
                tulis_karyawan      :   tulis_karyawan ,
                nomor_telepon       :   nomor_telepon,
                alamat              :   alamat,
                tanggal_masuk       :   tanggal_masuk,
                gaji_per_hari       :   gaji_per_hari,
                metode_gaji         :   metode_gaji ,
                gajibulan           :   gajibulan ,
                harikerja           :   harikerja ,
                gajihari            :   gajihari ,
                lembur              :   lembur ,
                metode_lembur       :   metode_lembur ,
                jamover             :   jamover ,
                overdanajam         :   overdanajam ,
                hariover            :   hariover ,
                overhari            :   overhari ,
                potongan            :   potongan ,
                potong_gaji         :   potong_gaji ,
                thr                 :   thr ,
                thr_gaji            :   thr_gaji ,
                bonus               : bonus ,
                bonus_gaji          : bonus_gaji ,
                keterangan          : keterangan ,
                cashbon             :   cashbon,
                nominal_cashbon     :   nominal_cashbon,
                metode_pembayaran   :  metode_pembayaran,
                check_kas           :  check_kas,
                tulis_pembayaran    :   tulis_pembayaran,
            },
            success: function(data) {
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
                    $("#form_input").load("{{ route('gaji.index', ['key' => 'input']) }}") ;
                    $("#riwayat_gaji").load("{{ route('gaji.index', ['key' => 'riwayat_gaji']) }}") ;
                    document.getElementById('notif-success').innerHTML  =   'Tambah gaji berhasil dilakukan';
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
});
</script>

<script>
    var url =   "{{ route('gaji.index', ['key' => 'riwayat_gaji']) }}";
$('#search').on('keyup', function(){
    $.ajax({
        url: url + "&search=" +$(this).val(),
        method: "GET",
        success: function(response) {
            $('#riwayat_gaji').html(response);
            $('#daftar_paginate .pagination a').on('click', function(e) {
                e.preventDefault();

                url = $(this).attr('href') ;
                $.ajax({
                    url: url,
                    method: "GET",
                    success: function(response) {
                        $('#riwayat_gaji').html(response);
                    }

                });
            });
        }

    });
})
</script>

@endsection

@section('content')
<div class="row">
    <div class="col-md-5">
        <div class="card">
            <div class="card-header">Tambah Gaji</div>
            <div class="card-body">
                <div id="form_input"></div>
            </div>
        </div>
    </div>

    <div class="col-md-7">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('gaji.index') }}" method="get">
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
            <div class="card-header">Daftar Gaji</div>
            <div class="card-body">
                <div class="form-group">
                    <input type="text" name="search" id="search" autocomplete="off" placeholder="Pencarian..."
                        class="form-control search-data">
                </div>

                <div id="riwayat_gaji"></div>
            </div>
        </div>
    </div>
</div>
@endsection

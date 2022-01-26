@extends('layouts.main')

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
    $("#input_mutasi").load("{{ route('mutasikas.index', ['key' => 'input_mutasi']) }}") ;
    $("#riwayat_mutasi").load("{{ route('mutasikas.index', ['key' => 'riwayat_mutasi']) }}") ;
</script>

<script>
$(document).ready(function() {
    $(document).on('click', '#btnInput', function() {
        var tanggal_mutasi      =   $("#tanggal_mutasi").val() ;
        var dari_kas            =   $("#dari_kas").val() ;
        var transfer_ke         =   $("#transfer_ke").val() ;
        var nominal_mutasi      =   $("#nominal_mutasi").val() ;

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            url: "{{ route('mutasikas.store') }}",
            method: "POST",
            data: {
                tanggal_mutasi  :   tanggal_mutasi ,
                dari_kas        :   dari_kas ,
                transfer_ke     :   transfer_ke ,
                nominal_mutasi  :   nominal_mutasi
            },
            success: function(data) {
                if (data.status == 400) {
                    document.getElementById('notif-error').innerHTML  =   data.msg;
                    document.getElementById('notif-error').style      =   '';
                } else {
                    $("#input_mutasi").load("{{ route('mutasikas.index', ['key' => 'input_mutasi']) }}") ;
                    $("#riwayat_mutasi").load("{{ route('mutasikas.index', ['key' => 'riwayat_mutasi']) }}") ;
                    document.getElementById('notif-success').innerHTML  =   'Mutasi kas berhasil dilakukan';
                    document.getElementById('notif-success').style      =   '';
                }

                $('#topbar-notification').fadeIn();
                setTimeout(function() {
                    $('#topbar-notification').fadeOut();
                    document.getElementById('notif-error').style    =   'display: none';
                    document.getElementById('notif-success').style  =   'display: none';
                }, 3000) ;
            }
        });
    });
});

$(document).ready(function() {
    $(document).on('click', '#hapus_mutasi', function() {
        var id  =   $(this).data('id') ;

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            url: "{{ route('mutasikas.destroy') }}",
            method: "DELETE",
            data: {
                id  :   id ,
            },
            success: function(data) {
                if (data.status == 400) {
                    document.getElementById('notif-error').innerHTML  =   data.msg;
                    document.getElementById('notif-error').style      =   '';
                } else {
                    $("#input_mutasi").load("{{ route('mutasikas.index', ['key' => 'input_mutasi']) }}") ;
                    $("#riwayat_mutasi").load("{{ route('mutasikas.index', ['key' => 'riwayat_mutasi']) }}") ;
                    document.getElementById('notif-success').innerHTML  =   'Hapus mutasi kas berhasil dilakukan';
                    document.getElementById('notif-success').style      =   '';
                }

                $('#topbar-notification').fadeIn();
                setTimeout(function() {
                    $('#topbar-notification').fadeOut();
                    document.getElementById('notif-error').style    =   'display: none';
                    document.getElementById('notif-success').style  =   'display: none';
                }, 3000) ;
            }
        });
    });
});
</script>

<script>
var url =   "{{ route('mutasikas.index', ['key' => 'riwayat_mutasi']) }}";
$('#search').on('keyup', function(){
    $.ajax({
        url: url + "&search=" +$(this).val(),
        method: "GET",
        success: function(response) {
            $('#riwayat_mutasi').html(response);

            $('#daftar_paginate .pagination a').on('click', function(e) {
                e.preventDefault();

                url = $(this).attr('href') ;
                $.ajax({
                    url: url,
                    method: "GET",
                    success: function(response) {
                        $('#riwayat_mutasi').html(response);
                    }

                });
            });
        }

    });
})
</script>
@endsection

@section('title', 'Jurnal Mutasi Kas')

@section('content')
<div class="row">
    <div class="col-md-5">
        <div class="card">
            <div class="card-header">Form Mutasi</div>
            <div id="input_mutasi"></div>
        </div>
    </div>

    <div class="col-md-7">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('mutasikas.index') }}" method="get">
                    <input type="hidden" name="key" value="unduh">
                    <div class="row">
                        <div class="col-lg col-6"><input type="date" name="mulai" class="form-control"></div>
                        <div class="col-lg col-6"><input type="date" name="selesai" class="form-control"></div>
                        <div class="col-xl-auto col-12 pl-xl-1 mt-3 mt-xl-0">
                            <button type="submit" class="btn btn-outline-success btn-block"><i class="fa fa-file-excel-o"></i> Unduh</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="card">
            <div class="card-header">Riwayat Mutasi</div>
            <div class="card-body">
                <div class="form-group">
                    <input type="text" name="search" id="search" autocomplete="off" placeholder="Pencarian..." class="form-control search-data">
                </div>

                <div id="riwayat_mutasi"></div>
            </div>
        </div>
    </div>
</div>
@endsection

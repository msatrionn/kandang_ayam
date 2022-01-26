@extends('layouts.main')

@section('title', 'Daftar Strain')

@section('header')
<style>
    .radio-toolbar input[type="radio"] {
        opacity: 0;
        position: fixed;
        width: 0;
    }

    .radio-toolbar input[type="radio"] {
        opacity: 0;
        position: fixed;
        width: 0;
    }

    .radio-toolbar label {
        display: inline-block;
        background-color: #fff;
        padding: 8px 10px;
        font-size: 12px;
        border: 1px solid #999;
        border-radius: 5px;
        width: 100%;
    }

    .radio-toolbar input[type="radio"]:checked+label {
        background-color: #b1eef5;
        border-color: #01b2c6;
    }

</style>
@endsection

@section('footer')
<script>
$("#show_strain").load("{{ route('strain.index', ['key' => 'show']) }}");

$(document).on('click', '.ubah_strain', function() {
    var row_id      =   $(this).data('id') ;
    var nama_strain =   $("#nama_strain" + row_id).val() ;

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $.ajax({
        url     : "{{ route('strain.update') }}",
        method  : "PATCH",
        data    : {
            row_id      :   row_id,
            nama_strain :   nama_strain
        },
        success: function(data) {
            $("#show_strain").load("{{ route('strain.index', ['key' => 'show']) }}&tab=" + row_id);
        },
        error: function(data) {
            document.getElementById('notif-error').innerHTML  =   'Data tidak lengkap';
            document.getElementById('notif-error').style      =   '';
            $('#topbar-notification').fadeIn();
            setTimeout(function() {
                $('#topbar-notification').fadeOut();
                document.getElementById('notif-error').style    =   'display: none';
                document.getElementById('notif-success').style  =   'display: none';
            }, 3000) ;
        }
    });
});

$(document).on('click', '.add_standar', function() {
    var row_id      =   $(this).data('id') ;
    var nama_strain =   $('input:radio[name=jenis]:checked').val() ;
    var minggu_umur =   $("#minggu_umur" + row_id).val() ;
    var mulai_umur  =   $("#mulai_umur" + row_id).val() ;
    var sampai_umur =   $("#sampai_umur" + row_id).val() ;
    var standar     =   $("#standar" + row_id).val() ;

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $.ajax({
        url     : "{{ route('strain.store') }}",
        method  : "POST",
        data    : {
            row_id      :   row_id,
            nama_strain :   nama_strain,
            minggu_umur :   minggu_umur,
            mulai_umur  :   mulai_umur,
            sampai_umur :   sampai_umur,
            standar     :   standar,
            key         :   'standar'
        },
        success: function(data) {
            $("#show_strain").load("{{ route('strain.index', ['key' => 'show']) }}&tab=" + row_id);
            document.getElementById('notif-success').innerHTML  =   'Data berhasil ditambahkan';
            document.getElementById('notif-success').style      =   '';
            $('#topbar-notification').fadeIn();
            setTimeout(function() {
                $('#topbar-notification').fadeOut();
                document.getElementById('notif-error').style    =   'display: none';
                document.getElementById('notif-success').style  =   'display: none';
            }, 3000) ;
        },
        error: function(data) {
            document.getElementById('notif-error').innerHTML  =   'Data tidak lengkap';
            document.getElementById('notif-error').style      =   '';
            $('#topbar-notification').fadeIn();
            setTimeout(function() {
                $('#topbar-notification').fadeOut();
                document.getElementById('notif-error').style    =   'display: none';
                document.getElementById('notif-success').style  =   'display: none';
            }, 3000) ;
        }
    });
});

$(document).on('click', '.hapus_standar', function() {
    var row_id      =   $(this).data('id') ;
    var strain      =   $(this).data('strain') ;

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $.ajax({
        url     : "{{ route('strain.destroy') }}",
        method  : "DELETE",
        data    : {
            row_id  :   row_id,
            strain  :   strain,
        },
        success: function(data) {
            $("#show_strain").load("{{ route('strain.index', ['key' => 'show']) }}&tab=" + strain);
            document.getElementById('notif-success').innerHTML  =   'Data berhasil dihapus';
            document.getElementById('notif-success').style      =   '';
            $('#topbar-notification').fadeIn();
            setTimeout(function() {
                $('#topbar-notification').fadeOut();
                document.getElementById('notif-error').style    =   'display: none';
                document.getElementById('notif-success').style  =   'display: none';
            }, 3000) ;
        },
        error: function(data) {
            document.getElementById('notif-error').innerHTML  =   'Data gagal dihapus';
            document.getElementById('notif-error').style      =   '';
            $('#topbar-notification').fadeIn();
            setTimeout(function() {
                $('#topbar-notification').fadeOut();
                document.getElementById('notif-error').style    =   'display: none';
                document.getElementById('notif-success').style  =   'display: none';
            }, 3000) ;
        }
    });
});

$(document).on('click', '.ubah_standar', function() {
    var row_id      =   $(this).data('id') ;
    var strain      =   $(this).data('strain') ;
    var minggu      =   $("#minggu" + row_id).val() ;
    var dari        =   $("#dari" + row_id).val() ;
    var sampai      =   $("#sampai" + row_id).val() ;
    var angka       =   $("#angka" + row_id).val() ;

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $.ajax({
        url     : "{{ route('strain.standar') }}",
        method  : "PUT",
        data    : {
            row_id  :   row_id,
            strain  :   strain,
            minggu  :   minggu,
            dari    :   dari,
            sampai  :   sampai,
            angka   :   angka,
        },
        success: function(data) {
            if (data.status == 400) {
                document.getElementById('notif-error').innerHTML    =   data.msg;
                document.getElementById('notif-error').style        =   '';
            } else {
                document.getElementById('notif-success').innerHTML  =   'Data berhasil diperbaharui';
                document.getElementById('notif-success').style      =   '';
            }

            $('#topbar-notification').fadeIn();
            setTimeout(function() {
                $('#topbar-notification').fadeOut();
                document.getElementById('notif-error').style    =   'display: none';
                document.getElementById('notif-success').style  =   'display: none';
            }, 3000) ;
        },
        error: function(data) {
            document.getElementById('notif-error').innerHTML    =   'Data gagal dihapus';
            document.getElementById('notif-error').style        =   '';
            $('#topbar-notification').fadeIn();
            setTimeout(function() {
                $('#topbar-notification').fadeOut();
                document.getElementById('notif-error').style    =   'display: none';
                document.getElementById('notif-success').style  =   'display: none';
            }, 3000) ;
        }
    });
});
</script>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">Tambah Strain</div>
            <div class="card-body">
                <form action="{{ route('strain.store') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        Nama Strain
                        <input type="text" name="nama_strain" class="form-control" id="nama_strain" placeholder="Tuliskan Nama Strain" value="{{ old('nama_strain') }}" autocomplete="off">
                        @error("nama_strain") <div class="small text-danger">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-group text-right">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">Daftar Strain</div>
            <div id="show_strain"></div>
        </div>
    </div>
</div>
@endsection

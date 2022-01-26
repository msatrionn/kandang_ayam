@extends('layouts.main')

@section('title', 'Cut Off Stock')

@section('header')
<link rel="stylesheet" href="{{ asset('assets/vendor/select2/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/vendor/select2/select2-bootstrap4.min.css') }}">
@endsection

@section('footer')
<script>
$("#input_data").load("{{ route('cutoff.index', ['key' => 'input']) }}");
$("#data_cutoff").load("{{ route('cutoff.index', ['key' => 'data_cutoff']) }}");
</script>

<script src="{{ asset('assets/vendor/select2/select2.js') }}"></script>

<script>
$(document).ready(function() {
    $(document).on('click', '#input_cutoff', function() {
        var item    =   $("#item").val();
        var jumlah  =   $("#jumlah").val();
        var tanggal =   $("#tanggal").val();

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            url: "{{ route('cutoff.store') }}",
            method: "POST",
            data: {
                item    :   item,
                jumlah  :   jumlah,
                tanggal :   tanggal,
            },
            success: function(data) {
                if (data.status == 400) {
                    document.getElementById('notif-error').innerHTML = data.msg;
                    document.getElementById('notif-error').style = '';
                } else {
                    document.getElementById('notif-success').innerHTML = "Input cutoff berhasil";
                    document.getElementById('notif-success').style = '';
                    $("#input_data").load("{{ route('cutoff.index', ['key' => 'input']) }}");
                    $("#data_cutoff").load("{{ route('cutoff.index', ['key' => 'data_cutoff']) }}");
                }
                $('#topbar-notification').fadeIn();
                setTimeout(function() {
                    $('#topbar-notification').fadeOut();
                    document.getElementById('notif-error').style    =   'display: none';
                    document.getElementById('notif-success').style  =   'display: none';
                }, 3000);
            }
        });
    });
});
</script>

<script>
$(document).ready(function() {
    $(document).on('click', '#ubah_data', function() {
        var id      =   $(this).data('id') ;
        var jumlah  =   $("#jumlah_ubah" + id).val();
        var tanggal =   $("#tanggal_ubah" + id).val();

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            url: "{{ route('cutoff.patch') }}",
            method: "PATCH",
            data: {
                id      :   id,
                jumlah  :   jumlah,
                tanggal :   tanggal,
            },
            success: function(data) {
                if (data.status == 400) {
                    document.getElementById('notif-error').innerHTML = data.msg;
                    document.getElementById('notif-error').style = '';
                } else {
                    $('.modal-backdrop').remove();
                    $('body').removeClass('modal-open');
                    document.getElementById('notif-success').innerHTML = "Ubah data cutoff berhasil";
                    document.getElementById('notif-success').style = '';
                    $("#input_data").load("{{ route('cutoff.index', ['key' => 'input']) }}");
                    $("#data_cutoff").load("{{ route('cutoff.index', ['key' => 'data_cutoff']) }}");
                }
                $('#topbar-notification').fadeIn();
                setTimeout(function() {
                    $('#topbar-notification').fadeOut();
                    document.getElementById('notif-error').style    =   'display: none';
                    document.getElementById('notif-success').style  =   'display: none';
                }, 3000);
            }
        });
    });
});
</script>

<script>
$(document).ready(function() {
    $(document).on('click', '.hapus_cutoff', function() {
        var id  =   $(this).data('id');

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            url: "{{ route('cutoff.destroy') }}",
            method: "DELETE",
            data: {
                id  :   id,
            },
            success: function(data) {
                if (data.status == 400) {
                    document.getElementById('notif-error').innerHTML = data.msg;
                    document.getElementById('notif-error').style = '';
                } else {
                    document.getElementById('notif-success').innerHTML = "Hapus cutoff berhasil";
                    document.getElementById('notif-success').style = '';
                    $("#input_data").load("{{ route('cutoff.index', ['key' => 'input']) }}");
                    $("#data_cutoff").load("{{ route('cutoff.index', ['key' => 'data_cutoff']) }}");
                }
                $('#topbar-notification').fadeIn();
                setTimeout(function() {
                    $('#topbar-notification').fadeOut();
                    document.getElementById('notif-error').style    =   'display: none';
                    document.getElementById('notif-success').style  =   'display: none';
                }, 3000);
            }
        });
    });
});
</script>
@endsection

@section('content')
<div class="row">
    <div class="col-md-5">
        <div class="card">
            <div class="card-header">Input Cut Off</div>
            <div class="card-body">
                <div id="input_data"></div>
            </div>
        </div>
    </div>
    <div class="col-md-7">
        <div class="card">
            <div class="card-header">Daftar Cut Off</div>
            <div class="card-body">
                <div id="data_cutoff"></div>
            </div>
        </div>
    </div>
</div>
@endsection

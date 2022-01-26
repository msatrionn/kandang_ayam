@extends('layouts.main')

@section('title', 'Jurnal Cashbon')

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
        $("#input_cashbon").load("{{ route('cashbon.index', ['key' => 'input']) }}");
    </script>

    <script>
        function pilih_karyawan() {
            $(document).ready(function() {
                var id = $("#karyawan").val();

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    url: "{{ route('cashbon.index', ['key' => 'detail']) }}",
                    method: "GET",
                    data: {
                        id: id,
                    },
                    success: function(data) {
                        $("#detail_input").empty().append(data);
                        $("#riwayat_cashbon").load(
                            "{{ route('cashbon.index', ['key' => 'riwayat']) }}&id=" + id);
                    }
                });
            });
        }
    </script>

    <script>
        $(document).ready(function() {
            $(document).on('click', '#proses_cashbon', function() {
                var karyawan = $("#karyawan").val();
                var tanggal = $("#tanggal").val();
                var nominal = $("#nominal").val();
                var metode_pembayaran = $("#metode_pembayaran").val();
                var check_kas = $("#check_kas:checked").val();
                var tulis_pembayaran = $("#tulis_pembayaran").val();

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    url: "{{ route('cashbon.store') }}",
                    method: "POST",
                    data: {
                        karyawan: karyawan,
                        tanggal: tanggal,
                        nominal: nominal,
                        metode_pembayaran: metode_pembayaran,
                        check_kas: check_kas,
                        tulis_pembayaran: tulis_pembayaran,
                    },
                    success: function(data) {
                        if (data.status == 400) {
                            document.getElementById('notif-error').innerHTML = data.msg;
                            document.getElementById('notif-error').style = '';
                            $('#topbar-notification').fadeIn();
                            setTimeout(function() {
                                $('#topbar-notification').fadeOut();
                                document.getElementById('notif-error').style =
                                    'display: none';
                                document.getElementById('notif-success').style =
                                    'display: none';
                            }, 3000);
                        } else {
                            $.ajax({
                                url: "{{ route('cashbon.index', ['key' => 'detail']) }}",
                                method: "GET",
                                data: {
                                    id: karyawan,
                                },
                                success: function(data) {
                                    $("#detail_input").empty().append(data);
                                    $("#riwayat_cashbon").load("{{ route('cashbon.index', ['key' => 'riwayat']) }}&id=" + karyawan);
                                    document.getElementById('notif-success').innerHTML  = 'Tambah cashbon berhasil dilakukan';
                                    document.getElementById('notif-success').style      = '';
                                    $('#topbar-notification').fadeIn();
                                    setTimeout(function() {
                                        $('#topbar-notification').fadeOut();
                                        document.getElementById('notif-error').style    = 'display: none';
                                        document.getElementById('notif-success').style  = 'display: none';
                                    }, 3000);
                                }
                            });
                        }
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
                <div class="card-header">Form Cashbon</div>
                <div class="card-body">
                    <div id="input_cashbon"></div>

                    <div id="detail_input"></div>
                </div>
            </div>
        </div>
        <div class="col-md-7">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('cashbon.index') }}" method="get">
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
                <div class="card-header">Riwayat Cashbon</div>
                <div class="card-body">
                    <div id="riwayat_cashbon"></div>
                </div>
            </div>
        </div>
    </div>
@endsection

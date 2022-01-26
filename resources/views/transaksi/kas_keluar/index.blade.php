@extends('layouts.main')

@section('title', 'Pembayaran Purchase Order')

@section('header')
<link rel="stylesheet" href="{{ asset('js/vendor/politespace/politespace.css') }}">
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
<script src="{{ asset('js/vendor/politespace/libs/libs.js') }}"></script>
<script src="{{ asset('js/vendor/politespace/politespace.js') }}"></script>
<script src="{{ asset('js/vendor/politespace/politespace-init.js') }}"></script>
<link rel="stylesheet" href="{{ asset('assets/vendor/select2/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/vendor/select2/select2-bootstrap4.min.css') }}">
@endsection

@section('footer')
<script src="{{ asset('assets/vendor/select2/select2.js') }}"></script>
<script>
    $("#data_purchase").load("{{ route('paypurchase.index', ['key' => 'purchase']) }}&pay={{ $request->pay }}")
    $("#input_kas").load("{{ route('paypurchase.index', ['key' => 'input']) }}")
    $("#history_report").load("{{ route('paypurchase.index', ['key' => 'riwayat']) }}")

    $(document).ready(function() {
        $(document).on('click', '.input_pengeluaran', function(e) {
            e.preventDefault();
            $(document).find("div.text-danger").remove();

            var purchase                =   $('input:radio[name=purchase]:checked').val() ;
            var metode_pembayaran       =   $("#metode_pembayaran").val() ;
            var tanggal_pembayaran      =   $("#tanggal_pembayaran").val() ;
            var nominal_dibayarkan      =   $('#nominal_dibayarkan').val();
            var tulis_pembayaran        =   $('#tulis_pembayaran').val();
            var check_kas               =   $('#check_kas:checked').val();

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                url: "{{ route('paypurchase.store') }}",
                method: "POST",
                data: {
                    purchase            :   purchase,
                    metode_pembayaran   :   metode_pembayaran,
                    tanggal_pembayaran  :   tanggal_pembayaran,
                    nominal_dibayarkan  :   nominal_dibayarkan,
                    tulis_pembayaran    :   tulis_pembayaran,
                    check_kas           :   check_kas,
                    key                 :   'input'
                },
                success: function(data) {
                    $("#data_purchase").load("{{ route('paypurchase.index', ['key' => 'purchase']) }}&pay={{ $request->pay }}")
                    $("#input_kas").load("{{ route('paypurchase.index', ['key' => 'input']) }}")
                    $("#history_report").load("{{ route('paypurchase.index', ['key' => 'riwayat']) }}")
                    document.getElementById('notif-success').innerHTML  =   'Transaksi Berhasil Diselesaikan';
                    document.getElementById('notif-success').style      =   '';
                    $('#topbar-notification').fadeIn();
                    setTimeout(function() {
                        $('#topbar-notification').fadeOut();
                        document.getElementById('notif-error').style    =   'display: none';
                        document.getElementById('notif-success').style  =   'display: none';
                    }, 3000) ;
                },

                error: function(data) {
                    $.each(data.responseJSON.errors,function(field_name,error){
                        if ((field_name == 'metode_bayar') && (field_name='tulis_pembayaran')) {
                            document.getElementById('errPayment').innerHTML =   '<div class="small text-left text-danger">' + error + '</div>';
                        } else {
                            $(document).find('[name='+ field_name +']').after('<div class="small text-left text-danger">' + error + '</div>')
                        }
                    });
                }
            });
        });

        $(document).on('click', '.ubah_data', function(e) {
            e.preventDefault();
            $(document).find("div.text-danger").remove();

            var row_id              =   $(this).data('id') ;
            var metode_bayar        =   $("#metode_bayar" + row_id).val() ;
            var tanggal_bayar       =   $("#tanggal_bayar" + row_id).val() ;
            var nominal_dibayarkan  =   $("#nominal_dibayarkan" + row_id).val() ;

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                url: "{{ route('paypurchase.store') }}",
                method: "POST",
                data: {
                    row_id              :   row_id,
                    metode_bayar        :   metode_bayar,
                    tanggal_bayar       :   tanggal_bayar,
                    nominal_dibayarkan  :   nominal_dibayarkan,
                    key                 :   'update'
                },
                success: function(data) {
                    $('.modal-backdrop').remove();
                    $('body').removeClass('modal-open');
                    $("#data_purchase").load("{{ route('paypurchase.index', ['key' => 'purchase']) }}&pay={{ $request->pay }}")
                    $("#input_kas").load("{{ route('paypurchase.index', ['key' => 'input']) }}")
                    $("#history_report").load("{{ route('paypurchase.index', ['key' => 'riwayat']) }}")
                    document.getElementById('notif-success').innerHTML  =   'Ubah purchase order berhasil';
                    document.getElementById('notif-success').style      =   '';
                    $('#topbar-notification').fadeIn();
                    setTimeout(function() {
                        $('#topbar-notification').fadeOut();
                        document.getElementById('notif-error').style    =   'display: none';
                        document.getElementById('notif-success').style  =   'display: none';
                    }, 3000) ;
                },

                error: function(data) {
                    $.each(data.responseJSON.errors,function(field_name,error){
                        if (field_name == 'metode_bayar') {
                            document.getElementById('errPay').innerHTML =   '<div class="small text-left text-danger">' + error + '</div>';
                        } else {
                            $(document).find('[name='+ field_name +']').after('<div class="small text-left text-danger">' + error + '</div>')
                        }
                    });
                }
            });
        });
    });
</script>
@endsection

@section('content')
<div class="row">
    <div class="col-12 col-md-6">
        <div class="card">
            <div class="card-header">Daftar Purchase Order</div>
            <div class="card-body">
                <div id="data_purchase"></div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">Input Pembayaran Purchase Order</div>
            <div class="card-body">
                <div id="input_kas"></div>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-6">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('paypurchase.index') }}" method="get">
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
        <div id="history_report"></div>
    </div>
</div>

@endsection

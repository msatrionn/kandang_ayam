@extends('layouts.main')

@section('title', 'Delivery Order')

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
<link rel="stylesheet" href="{{ asset('assets/vendor/select2/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/vendor/select2/select2-bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('js/vendor/politespace/politespace.css') }}">
@endsection

@section('footer')
<script>
    $("#data_purchase").load("{{ route('delivery.purchase') }}");
    $("#data_daftar").load("{{ route('delivery.daftar') }}");
    $("#diterima").load("{{ route('delivery.input') }}");

    $(document).ready(function() {

        $(document).on('click', '.input_delivery', function(e) {
            e.preventDefault();
            $(document).find("div.text-danger").remove();
            var nomor_purchase      =   $('input:radio[name=purchase]:checked').val() ;
            var nomor               =   $('input:radio[name=purchase]:checked').data('nomor') ;
            var produk              =   $('input:radio[name=purchase]:checked').data('produk') ;
            var tanggal_kirim       =   $("#tanggal_kirim").val();
            var jumlah_pengiriman   =   $("#jumlah_pengiriman").val();
            var biaya_pengiriman    =   $("#biaya_pengiriman").val();
            var beban_angkut        =   $("#beban_angkut").val();
            var biaya_lain_lain     =   $("#biaya_lain_lain").val();
            var metode_pembayaran   =   $("#metode_pembayaran").val();
            var tulis_pembayaran    =   $("#tulis_pembayaran").val();
            var check_kas           =   $("#check_kas:checked").val();
            var check_angkatan           =   $("#check_angkatan:checked").val();
            var pilih_kandang       =   $("[name=pilih_kandang]").val();
            var angkatan           =   $("[name=angkatan]").val();
            var pilih_kandang2           =   $("[name=pilih_kandang2]").val();
            var tulis_angkatan           =   $("#tulis_angkatan").val();
            var produk= $("[name=purchase]").attr('data-produk');
            var strain= $("[name=strain_select]").val();

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                url: "{{ route('delivery.store') }}",
                method: "POST",
                data: {
                    nomor_purchase      :   nomor_purchase,
                    nomor               :   nomor,
                    produk              :   produk,
                    tanggal_kirim       :   tanggal_kirim,
                    jumlah_pengiriman   :   jumlah_pengiriman,
                    biaya_pengiriman    :   biaya_pengiriman,
                    beban_angkut        :   beban_angkut,
                    angkatan        :   angkatan,
                    pilih_kandang        :   pilih_kandang,
                    pilih_kandang2        :   pilih_kandang2,
                    tulis_angkatan        :   tulis_angkatan,
                    biaya_lain_lain     :   biaya_lain_lain,
                    metode_pembayaran   :   metode_pembayaran,
                    tulis_pembayaran    :   tulis_pembayaran,
                    check_kas           :   check_kas,
                    strain_id           :   strain,
                    check_angkatan           :   check_angkatan,
                },
                success: function(data) {
                    console.log(data);
                    if (data.status == 400) {
                        document.getElementById('notif-error').innerHTML  =   data.msg;
                        document.getElementById('notif-error').style      =   '';
                        $('#topbar-notification').fadeIn();
                    } else {
                        $("#data_purchase").load("{{ route('delivery.purchase') }}");
                        $("#data_daftar").load("{{ route('delivery.daftar') }}");
                        $("#diterima").load("{{ route('delivery.input') }}");
                        document.getElementById('notif-success').innerHTML  =   'Input Barang Diterima Berhasil';
                        document.getElementById('notif-success').style      =   '';
                        $('#topbar-notification').fadeIn();
                    }

                    setTimeout(function() {
                        $('#topbar-notification').fadeOut();
                        document.getElementById('notif-error').style    =   'display: none';
                        document.getElementById('notif-success').style  =   'display: none';
                    }, 3000) ;
                },
                error: function(data) {
                    $.each(data.responseJSON.errors,function(field_name,error){
                        if (field_name == 'metode_pembayaran') {
                            document.getElementById('errPayment').innerHTML =   '<div class="small text-left text-danger">' + error + '</div>';
                        } else {
                            $(document).find('[name='+ field_name +']').after('<div class="small text-left text-danger">' + error + '</div>')
                        }
                    });
                }
            });
        });

        $(document).on('click', '.ubah_delivery', function(e) {
            e.preventDefault();
            $(document).find("div.text-danger").remove();
            var row_id              =   $(this).data('id') ;
            var tanggal             =   $("#tanggal" + row_id).val();
            var jumlah_kirim        =   $("#jumlah_kirim" + row_id).val();
            var biaya_kirim         =   $("#biaya_kirim" + row_id).val();
            var biaya_beban_angkut  =   $("#biaya_beban_angkut" + row_id).val();
            var biaya_lain          =   $("#biaya_lain" + row_id).val();
            var metode_bayar        =   $("#metode_bayar" + row_id).val();

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                url: "{{ route('delivery.update') }}",
                method: "POST",
                data: {
                    row_id              :   row_id,
                    tanggal             :   tanggal,
                    jumlah_kirim        :   jumlah_kirim,
                    biaya_kirim         :   biaya_kirim,
                    biaya_beban_angkut  :   biaya_beban_angkut,
                    biaya_lain          :   biaya_lain,
                    metode_bayar        :   metode_bayar,
                },
                success: function(data) {
                    $('.modal-backdrop').remove();
                    $('body').removeClass('modal-open');
                    $("#data_purchase").load("{{ route('delivery.purchase') }}");
                    $("#data_daftar").load("{{ route('delivery.daftar') }}");
                    $("#diterima").load("{{ route('delivery.input') }}");
                    document.getElementById('notif-success').innerHTML  =   'Input Barang Diterima Berhasil';
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
                        if (field_name == 'metode_pembayaran') {
                            document.getElementById('errPayment').innerHTML =   '<div class="small text-left text-danger">' + error + '</div>';
                        } else {
                            $(document).find('[name='+ field_name +']').after('<div class="small text-left text-danger">' + error + '</div>')
                        }
                    });
                }
            });
        });
    });
</script>
<script>
    $('#daftar_cari').on('keyup', function(e) {
         e.preventDefault();
         var url = "{{ route('delivery.search') }}";
         $.ajax({
             url: url,
             method: "GET",
             data:{
                 key:$(this).val()
             },
             success: function(response) {
                 // console.log(response);
                 $('#data_daftar').html(response);
             }

         });
     });
  </script>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                Daftar Purchsing Order
            </div>
            <div class="card-body">
                <div id="data_purchase"></div>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('delivery.index') }}" method="get">
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
            <div class="card-header">
                Daftar Delivery Order
            </div>
            <div class="card-body">

                <div id="diterima"></div>
                <input type="text" name="daftar_cari" id="daftar_cari" class="form-control mt-4" placeholder="Cari">
                <div id="data_daftar"></div>
            </div>
        </div>
    </div>
</div>

@endsection


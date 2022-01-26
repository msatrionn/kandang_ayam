@extends('layouts.main')

@section('title', 'Jurnal Penjualan Lain')

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
    $("#form_jual").load("{{ route('juallain.index', ['key' => 'form_input']) }}") ;
    $("#daftar_list").load("{{ route('juallain.index', ['key' => 'daftar_list']) }}") ;
    $("#form_trans").load("{{ route('juallain.index', ['key' => 'form_trans']) }}") ;
    $("#daftar_trans").load("{{ route('juallain.index', ['key' => 'daftar_trans']) }}") ;
</script>

<script>
    $("[name=cari]").on('keyup',function () {
        $.ajax({
            url:"{{ route('juallain.index', ['key' => 'daftar_list']) }}",
            method:"GET",
            data:{
                cari:$(this).val()
            },
            success: function(data){
                $("#daftar_list").html(data)
            }
        })
    })
</script>

<script>
    $(document).on('click', '#input_jual', function() {
        var kandang = [];
        $('.kandang').each(function(){
        kandang.push($(this).val());
        });
        var satuan = []
        $("[name=satuan]").each(function(){
        satuan.push($(this).val());
        });
        var check_satuan=[]
        $(".check_satuan:checked").each(function(){
        check_satuan.push($(this).val()) ;
        });
        var tulis_satuan = []
        $(".tulis_satuan").each(function(){
        tulis_satuan.push($(this).val())
        })

        var produk=[]
        $('.produk').each(function(){
        produk.push($(this).val());
        })
        var check_produk=[]
        $(".check_produk:checked").each(function(){
        check_produk.push($(this).val())
        })
        console.log(check_produk+kandang);
        var tulis_produk=[]
        $(".tulis_produk").each(function(){
        tulis_produk.push($(this).val())
        })
        var jumlah = []
        $("[name=jumlah]").each(function(){
        jumlah.push($(this).val())
        })
        var nominal = []
        $("[name=nominal]").each(function(){
        nominal.push($(this).val())
        })

        // console.log(
        //     "kandang"+kandang+
        //     "satuan"+satuan+
        //     "ceksatuan"+check_satuan+
        //     "tulis_satuan"+tulis_satuan+"produk"
        //     +produk
        //     +"check_produk"
        //     +check_produk
        //     +'tulis produk'
        //     +tulis_produk
        //     +'jumlah'+jumlah+
        //     "nominal"+nominal
        //     );

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            url: "{{ route('juallain.store') }}",
            method: "POST",
            data: {
                kandang         :   kandang,
                produk          :   produk,
                check_produk    :   check_produk,
                tulis_produk    :   tulis_produk,
                satuan          :   satuan,
                check_satuan    :   check_satuan,
                tulis_satuan    :   tulis_satuan,
                jumlah          :   jumlah,
                nominal         :   nominal,
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
                    $("#form_jual").load("{{ route('juallain.index', ['key' => 'form_input']) }}") ;
                    $("#daftar_list").load("{{ route('juallain.index', ['key' => 'daftar_list']) }}") ;

                    document.getElementById('notif-success').innerHTML  =   "Tambah penjualan produk berhasil";
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
        var id  =   $(this).data('id') ;

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            url: "{{ route('juallain.destroy') }}",
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
                    $("#form_jual").load("{{ route('juallain.index', ['key' => 'form_input']) }}") ;
                    $("#daftar_list").load("{{ route('juallain.index', ['key' => 'daftar_list']) }}") ;

                    document.getElementById('notif-success').innerHTML  =   "Daftar penjualan produk berhasil dihapus";
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

        var check_konsumen      =   $("#check_konsumen:checked").val() ;
        var nama_konsumen       =   $("#nama_konsumen").val() ;
        var nomor_telepon       =   $("#nomor_telepon").val() ;
        var alamat              =   $("#alamat").val() ;

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            url: "{{ route('juallain.update') }}",
            method: "PATCH",
            data: {
                konsumen            :   konsumen,
                perubahan           :   perubahan,
                tanggal             :   tanggal,
                nominal_bayar       :   nominal_bayar,
                metode_pembayaran   :   metode_pembayaran,
                tulis_pembayaran    :   tulis_pembayaran,
                check_kas           :   check_kas,
                check_konsumen      :   check_konsumen,
                nama_konsumen       :   nama_konsumen,
                nomor_telepon       :   nomor_telepon,
                alamat              :   alamat,
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
                    $("#form_jual").load("{{ route('juallain.index', ['key' => 'form_input']) }}") ;
                    $("#daftar_list").load("{{ route('juallain.index', ['key' => 'daftar_list']) }}") ;
                    $("#form_trans").load("{{ route('juallain.index', ['key' => 'form_trans']) }}") ;
                    $("#daftar_trans").load("{{ route('juallain.index', ['key' => 'daftar_trans']) }}") ;

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
        url: "{{ route('juallain.hapus') }}",
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
                $("#form_jual").load("{{ route('juallain.index', ['key' => 'form_input']) }}") ;
                $("#daftar_list").load("{{ route('juallain.index', ['key' => 'daftar_list']) }}") ;
                $("#form_trans").load("{{ route('juallain.index', ['key' => 'form_trans']) }}") ;
                $("#daftar_trans").load("{{ route('juallain.index', ['key' => 'daftar_trans']) }}") ;

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
    $(".tgl").on('change',function(){
    var tgl1 = $("[name=mulai]").val()
    var tgl2 = $("[name=selesai]").val()
        $.ajax({
           url:"{{ route('juallain.index', ['key' => 'daftar_list_tgl']) }}",
            method:"GET",
            data:{
            tgl1:tgl1,
            tgl2:tgl2
            },
            success: function(data){
            $("#daftar_list").html(data)
            }
            })
        })
</script>
@endsection

@section('content')
<div class="row">
    <div class="col-md-5">
        <div class="card">
            <div class="card-header">Form Transaksi</div>
            <div class="card-body">
                <div id="form_jual"></div>
            </div>
        </div>
    </div>

    <div class="col-md-7">
        <div class="card">
            <div class="card-header">Daftar Input Transaksi</div>
            <div class="card-body">
                <input type="text" class="form-control mb-2" name="cari" placeholder="Cari" />
                <div class="row mb-4">
                    <div class="col-lg col-6"><input type="date" name="mulai" class="form-control tgl" id="tgl"></div>
                    <div class="col-lg col-6"><input type="date" name="selesai" class="form-control tgl" id="tgl"></div>
                </div>
                <div id="daftar_list"></div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div id="form_trans"></div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div id="daftar_trans"></div>
            </div>
        </div>
    </div>
</div>
@endsection

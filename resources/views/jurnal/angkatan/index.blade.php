@extends('layouts.main')

@section('title', 'Jurnal Angkatan Ayam')

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

    .wrapper {
        position: relative;
        white-space: nowrap;
    }

    .sticky-head {
        position: -webkit-sticky;
        position: sticky;
        top: 0;
        background-color: #fff;
        z-index: 97;
    }

    .sticky-heading {
        top: 32px
    }

    .sticky-col {
        position: -webkit-sticky;
        position: sticky;
        background-color: #fff;
    }

    .number-col {
        width: 50px;
        min-width: 50px;
        max-width: 50px;
        left: 0px;
    }

    .first-col {
        width: 100px;
        min-width: 100px;
        max-width: 100px;
        left: 0px;
    }

    .second-col {
        width: 130px;
        min-width: 130px;
        max-width: 130px;
        left: 100px;
    }

    .hari-col {
        width: 70px;
        min-width: 70px;
        max-width: 70px;
        left: 100px;
    }

    .minggu-col {
        width: 60px;
        min-width: 60px;
        max-width: 60px;
        left: 170px;
    }
</style>
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
    function angkatan() {
    $(document).ready(function() {
    var row_id  =   $("#angkatan").val();

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            url: "{{ route('angkatanayam.index', ['key' => 'show_kandang']) }}",
            method: "GET",
            data: {
                row_id: row_id,
            },
            success: function(data) {
                $("#show_kandang").empty().append(data);
            }
        });
    });
}

$(document).on('click', '#mutasi_kandang', function(e) {
    e.preventDefault();
    $(document).find("div.text-danger").remove();

    var angkatan            =   $("#angkatan").val() ;
    var pilih_kandang       =   $("#pilih_kandang").val() ;
    var jumlah_mutasi       =   $("#jumlah_mutasi").val() ;
    var penempatan_kandang  =   $("#penempatan_kandang").val() ;
    var tanggal_mutasi      =   $("#tanggal_mutasi").val() ;

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $.ajax({
        url: "{{ route('angkatanayam.store') }}",
        method: "POST",
        data: {
            angkatan            :   angkatan,
            pilih_kandang       :   pilih_kandang,
            jumlah_mutasi       :   jumlah_mutasi,
            penempatan_kandang  :   penempatan_kandang,
            tanggal_mutasi      :   tanggal_mutasi,
            key                 :   'mutasi',
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
                $.ajax({
                    url: "{{ route('angkatanayam.index', ['key' => 'show_data']) }}",
                    method: "GET",
                    data: {
                        row_id  :   angkatan,
                        kandang :   pilih_kandang,
                        tab     :   'mutasi',
                    },
                    success: function(data) {
                        $("#show_data").empty().append(data);

                        $('.modal-backdrop').remove();
                        $('body').removeClass('modal-open');
                        document.getElementById('notif-success').innerHTML  =   'Mutasi ayam berhasil ditambahkan';
                        document.getElementById('notif-success').style      =   '';
                        $('#topbar-notification').fadeIn();
                        setTimeout(function() {
                            $('#topbar-notification').fadeOut();
                            document.getElementById('notif-error').style    =   'display: none';
                            document.getElementById('notif-success').style  =   'display: none';
                        }, 3000) ;
                    }
                });
            }
        }
    });
});

$(document).on('click', '#input_ovk', function(e) {
    e.preventDefault();
    $(document).find("div.text-danger").remove();

    var angkatan            =   $("#angkatan").val() ;
    var pilih_kandang       =   $("#pilih_kandang").val() ;
    var hari_ovk            =   $("#hari_ovk").val() ;
    var jenis_ovk           =   $("#jenis_ovk").val() ;
    var pilih_penerima      =   $("#pilih_penerima_ovk").val() ;
    var input_penerima      =   $("#input_penerima_ovk").val() ;
    var check_penerima      =   document.getElementById('check_penerima_ovk').checked ;
    var ovk_masuk           =   $("#ovk_masuk").val() ;
    var ovk_keluar          =   $("#ovk_keluar").val() ;
    var keterangan_ovk      =   $("#keterangan_ovk").val() ;

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $.ajax({
        url: "{{ route('angkatanayam.store') }}",
        method: "POST",
        data: {
            angkatan        :   angkatan,
            kandang         :   pilih_kandang,
            hari_ovk        :   hari_ovk,
            jenis_ovk       :   jenis_ovk,
            pilih_penerima  :   pilih_penerima,
            input_penerima  :   input_penerima,
            check_penerima  :   check_penerima,
            ovk_masuk       :   ovk_masuk,
            ovk_keluar      :   ovk_keluar,
            keterangan_ovk  :   keterangan_ovk,
            key             :   'ovk',
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
                $.ajax({
                    url: "{{ route('angkatanayam.index', ['key' => 'show_data']) }}",
                    method: "GET",
                    data: {
                        row_id  :   angkatan,
                        kandang :   pilih_kandang,
                        tab     :   'ovk',
                    },
                    success: function(data) {
                        $("#show_data").empty().append(data);

                        $('.modal-backdrop').remove();
                        $('body').removeClass('modal-open');
                        document.getElementById('notif-success').innerHTML  =   'Data ovk berhasil ditambahkan';
                        document.getElementById('notif-success').style      =   '';
                        $('#topbar-notification').fadeIn();
                        setTimeout(function() {
                            $('#topbar-notification').fadeOut();
                            document.getElementById('notif-error').style    =   'display: none';
                            document.getElementById('notif-success').style  =   'display: none';
                        }, 3000) ;
                    }
                });
            }
        }
    });
});

$(document).on('click', '#input_vaksinasi', function(e) {
    e.preventDefault();
    $(document).find("div.text-danger").remove();

    var row_id      =   $("#angkatan").val() ;
    var kandang     =   $("#pilih_kandang").val() ;
    var umur        =   $("#umur_vaksinasi").val() ;
    var vaksin      =   $("#vaksin_vaksinasi").val() ;
    var aplikasi    =   $("#aplikasi_vaksinasi").val() ;
    var realisasi   =   $("#realisasi_vaksinasi").val() ;

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $.ajax({
        url: "{{ route('angkatanayam.store') }}",
        method: "POST",
        data: {
            row_id      :   row_id,
            kandang     :   kandang,
            umur        :   umur,
            vaksin      :   vaksin,
            aplikasi    :   aplikasi,
            realisasi   :   realisasi,
            key         :   'vaksinasi',
        },
        success: function(data) {
            $.ajax({
                url: "{{ route('angkatanayam.index', ['key' => 'show_data']) }}",
                method: "GET",
                data: {
                    row_id  :   row_id,
                    kandang :   kandang,
                    tab     :   'vaksinasi',
                },
                success: function(data) {
                    document.getElementById('notif-success').innerHTML  =   'Data vaksinasi berhasil ditambahkan';
                    document.getElementById('notif-success').style      =   '';
                    $('#topbar-notification').fadeIn();
                    setTimeout(function() {
                        $('#topbar-notification').fadeOut();
                        document.getElementById('notif-error').style    =   'display: none';
                        document.getElementById('notif-success').style  =   'display: none';
                    }, 3000) ;

                    $("#show_data").empty().append(data);
                }
            });
        },
        error: function(data) {
            document.getElementById('notif-error').innerHTML  =   'Data belum lengkap';
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

$(document).on('click', '#input_populasi', function(e) {
    e.preventDefault();
    $(document).find("div.text-danger").remove();

    var angkatan        =   $("#angkatan").val() ;
    var pilih_kandang   =   $("#pilih_kandang").val() ;
    var hari_populasi   =   $("#hari_populasi").val() ;
    var populasi_mati   =   $("#populasi_mati").val() ;
    var populasi_afkir  =   $("#populasi_afkir").val() ;
    var populasi_panen  =   $("#populasi_panen").val() ;

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $.ajax({
        url: "{{ route('angkatanayam.store') }}",
        method: "POST",
        data: {
            angkatan            :   angkatan,
            kandang             :   pilih_kandang,
            hari_populasi       :   hari_populasi,
            populasi_mati       :   populasi_mati,
            populasi_afkir      :   populasi_afkir,
            populasi_panen      :   populasi_panen,
            key                 :   'populasi',
        },
        success: function(data) {
            $.ajax({
                url: "{{ route('angkatanayam.index', ['key' => 'show_data']) }}",
                method: "GET",
                data: {
                    row_id  :   angkatan,
                    kandang :   pilih_kandang,
                    tab     :   'populasi',
                },
                success: function(data) {
                    $("#show_data").empty().append(data);

                    $('.modal-backdrop').remove();
                    $('body').removeClass('modal-open');
                    document.getElementById('notif-success').innerHTML  =   'Data populasi berhasil diperbaharui';
                    document.getElementById('notif-success').style      =   '';
                    $('#topbar-notification').fadeIn();
                    setTimeout(function() {
                        $('#topbar-notification').fadeOut();
                        document.getElementById('notif-error').style    =   'display: none';
                        document.getElementById('notif-success').style  =   'display: none';
                    }, 3000) ;
                }
            });
        }
    });
});

$(document).on('click', '#input_pakan', function(e) {
    e.preventDefault();
    $(document).find("div.text-danger").remove();

    var angkatan            =   $("#angkatan").val() ;
    var pilih_kandang       =   $("#pilih_kandang").val() ;
    var hari_pakan          =   $("#hari_pakan").val() ;
    var jenis_pakan         =   $("#jenis_pakan").val() ;
    var pilih_penerima      =   $("#pilih_penerima").val() ;
    var input_penerima      =   $("#input_penerima").val() ;
    var check_penerima      =   document.getElementById('check_penerima').checked ;
    var pakan_masuk         =   $("#pakan_masuk").val() ;
    var pakan_keluar        =   $("#pakan_keluar").val() ;
    var pakan_keluar_awal        =   $("#pakan_keluar_awal").val() ;
    var keterangan_pakan    =   $("#keterangan_pakan").val() ;

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $.ajax({
        url: "{{ route('angkatanayam.store') }}",
        method: "POST",
        data: {
            angkatan            :   angkatan,
            kandang             :   pilih_kandang,
            hari_pakan          :   hari_pakan,
            jenis_pakan         :   jenis_pakan,
            pilih_penerima      :   pilih_penerima,
            input_penerima      :   input_penerima,
            check_penerima      :   check_penerima,
            pakan_masuk         :   pakan_masuk,
            pakan_keluar        :   pakan_keluar,
            pakan_keluar_awal        :   pakan_keluar_awal,
            keterangan_pakan    :   keterangan_pakan,
            key                 :   'pakan',
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
                $.ajax({
                    url: "{{ route('angkatanayam.index', ['key' => 'show_data']) }}",
                    method: "GET",
                    data: {
                        row_id  :   angkatan,
                        kandang :   pilih_kandang,
                        tab     :   'pakan',
                    },
                    success: function(data) {
                        $("#show_data").empty().append(data);
                        document.getElementById('notif-success').innerHTML  =   'Data pakan berhasil ditambahkan';
                        document.getElementById('notif-success').style      =   '';
                        $('#topbar-notification').fadeIn();
                        setTimeout(function() {
                            $('#topbar-notification').fadeOut();
                            document.getElementById('notif-error').style    =   'display: none';
                            document.getElementById('notif-success').style  =   'display: none';
                        }, 3000) ;
                    }
                });
            }
        }
    });
});

$(document).on('click', '#input_timbang', function() {
    var row_id  =   $("#angkatan").val() ;
    var kandang =   $("#pilih_kandang").val() ;
    var hari    =   $("#hari_timbang").val() ;
    var ekor    =   0;
    var total   =   0;
    var timbang =   document.getElementsByClassName('timbang_berat') ;
    var hitung  =   [];
    var ekoran  =   [];
    for(var i = 0; i < timbang.length; ++i) {
        var numb    =   timbang[i].value || 0;

        ekoran.push(parseFloat((numb > 0) ? 1 : 0)) ;
        hitung.push(parseFloat(numb));
    }

    total   =   hitung.reduce(function(prev, current, index, array){
                    return prev + current;
                }).toFixed(2);

    ekor    =   ekoran.reduce(function(prev, current, index, array){
                    return prev + current;
                });

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $.ajax({
        url: "{{ route('angkatanayam.store') }}",
        method: "POST",
        data: {
            row_id  :   row_id,
            kandang :   kandang,
            hari    :   hari,
            hitung  :   hitung,
            total   :   total,
            ekor    :   ekor,
            key     :   'timbang',
        },
        success: function(data) {
            $.ajax({
                url: "{{ route('angkatanayam.index', ['key' => 'show_data']) }}",
                method: "GET",
                data: {
                    row_id  :   row_id,
                    kandang :   kandang,
                    tab     :   'timbang',
                },
                success: function(data) {
                    $("#show_data").empty().append(data);
                    document.getElementById('notif-success').innerHTML  =   'Data timbang berhasil ditambahkan';
                    document.getElementById('notif-success').style      =   '';
                    $('#topbar-notification').fadeIn();
                    setTimeout(function() {
                        $('#topbar-notification').fadeOut();
                        document.getElementById('notif-error').style    =   'display: none';
                        document.getElementById('notif-success').style  =   'display: none';
                    }, 3000) ;
                }
            });
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
        },
    });
});

$(document).on('click', '#input_keterangan', function() {
    var row_id      =   $("#angkatan").val() ;
    var kandang     =   $("#pilih_kandang").val() ;
    var hari        =   $("#hari_catatan").val() ;
    var keterangan  =   $("#keterangan_catatan").val() ;

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $.ajax({
        url: "{{ route('angkatanayam.store') }}",
        method: "POST",
        data: {
            row_id      :   row_id,
            kandang     :   kandang,
            hari        :   hari,
            keterangan  :   keterangan,
            key         :   'catatan',
        },
        success: function(data) {
            $.ajax({
                url: "{{ route('angkatanayam.index', ['key' => 'show_data']) }}",
                method: "GET",
                data: {
                    row_id  :   row_id,
                    kandang :   kandang,
                    tab     :   'catatan',
                },
                success: function(data) {
                    $("#show_data").empty().append(data);
                    document.getElementById('notif-success').innerHTML  =   'Data keterangan berhasil diperbaharui';
                    document.getElementById('notif-success').style      =   '';
                    $('#topbar-notification').fadeIn();
                    setTimeout(function() {
                        $('#topbar-notification').fadeOut();
                        document.getElementById('notif-error').style    =   'display: none';
                        document.getElementById('notif-success').style  =   'display: none';
                    }, 3000) ;
                }
            });
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
        },
    });
});

$(document).on('click', '#submit_penyakit', function() {
    var row_id              =   $("#angkatan").val() ;
    var kandang             =   $("#pilih_kandang").val() ;
    var tanggal_penyakit    =   $("#tanggal_penyakit").val() ;
    var penyakit            =   $("#penyakit").val() ;
    var input_penyakit      =   $("#input_penyakit").val() ;
    var check_penyakit      =   document.getElementById('check_penyakit').checked ;
    var keterangan_penyakit =   $("#keterangan_penyakit").val() ;

    var formData = new FormData();
    formData.append('unggah_foto', $('#unggah_foto')[0].files[0]);
    formData.append('row_id', row_id);
    formData.append('kandang', kandang);
    formData.append('tanggal_penyakit', tanggal_penyakit);
    formData.append('penyakit', penyakit);
    formData.append('input_penyakit', input_penyakit);
    formData.append('check_penyakit', check_penyakit);
    formData.append('keterangan_penyakit', keterangan_penyakit);
    formData.append('key', 'kasus');

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $.ajax({
        url     : "{{ route('angkatanayam.store') }}",
        method  : "POST",
        cache: false,
        contentType: false,
        processData: false,
        data    : formData,
        success: function(data) {
            if (data.status == 400) {
                document.getElementById('notif-error').innerHTML  =   'Data tidak lengkap';
                document.getElementById('notif-error').style      =   '';
                $('#topbar-notification').fadeIn();
                setTimeout(function() {
                    $('#topbar-notification').fadeOut();
                    document.getElementById('notif-error').style    =   'display: none';
                    document.getElementById('notif-success').style  =   'display: none';
                }, 3000) ;
            } else {
                $.ajax({
                    url: "{{ route('angkatanayam.index', ['key' => 'show_data']) }}",
                    method: "GET",
                    data: {
                        row_id  :   row_id,
                        kandang :   kandang,
                        tab     :   'kasus',
                    },
                    success: function(data) {
                        $("#show_data").empty().append(data);
                        document.getElementById('notif-success').innerHTML  =   'Data penyakit berhasil diperbaharui';
                        document.getElementById('notif-success').style      =   '';
                        $('#topbar-notification').fadeIn();
                        setTimeout(function() {
                            $('#topbar-notification').fadeOut();
                            document.getElementById('notif-error').style    =   'display: none';
                            document.getElementById('notif-success').style  =   'display: none';
                        }, 3000) ;
                    }
                });
            }
        },
    });
});

$(document).on('click', '.hapus_vaksinasi', function(e) {
    e.preventDefault();
    $(document).find("div.text-danger").remove();

    var row_id  =   document.getElementById('angkatan').value ;
    var kandang =   document.getElementById('pilih_kandang').value ;
    var vaksin  =   $(this).data('id') ;

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $.ajax({
        url: "{{ route('angkatanayam.destroy') }}",
        method: "PATCH",
        data: {
            row_id  :   row_id,
            vaksin  :   vaksin,
            key     :   'vaksinasi'
        },
        success: function(data) {
            $.ajax({
                url: "{{ route('angkatanayam.index', ['key' => 'show_data']) }}",
                method: "GET",
                data: {
                    row_id  :   row_id,
                    kandang :   kandang,
                    tab     :   'vaksinasi',
                },
                success: function(data) {
                    $("#show_data").empty().append(data);

                    $('.modal-backdrop').remove();
                    $('body').removeClass('modal-open');
                    document.getElementById('notif-success').innerHTML  =   'Hapus vaksinasi berhasil';
                    document.getElementById('notif-success').style      =   '';
                    $('#topbar-notification').fadeIn();
                    setTimeout(function() {
                        $('#topbar-notification').fadeOut();
                        document.getElementById('notif-error').style    =   'display: none';
                        document.getElementById('notif-success').style  =   'display: none';
                    }, 3000) ;
                }
            });
        }
    });
});

$(document).on('click', '.hapus_pakan', function(e) {
    e.preventDefault();
    $(document).find("div.text-danger").remove();

    var row_id  =   document.getElementById('angkatan').value ;
    var kandang =   document.getElementById('pilih_kandang').value ;
    var pakan   =   $(this).data('id') ;

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $.ajax({
        url: "{{ route('angkatanayam.destroy') }}",
        method: "PATCH",
        data: {
            row_id  :   row_id,
            pakan   :   pakan,
            key     :   'pakan'
        },
        success: function(data) {
            $.ajax({
                url: "{{ route('angkatanayam.index', ['key' => 'show_data']) }}",
                method: "GET",
                data: {
                    row_id  :   row_id,
                    kandang :   kandang,
                    tab     :   'pakan',
                },
                success: function(data) {
                    $("#show_data").empty().append(data);

                    $('.modal-backdrop').remove();
                    $('body').removeClass('modal-open');
                    document.getElementById('notif-success').innerHTML  =   'Hapus kartu stok pakan berhasil';
                    document.getElementById('notif-success').style      =   '';
                    $('#topbar-notification').fadeIn();
                    setTimeout(function() {
                        $('#topbar-notification').fadeOut();
                        document.getElementById('notif-error').style    =   'display: none';
                        document.getElementById('notif-success').style  =   'display: none';
                    }, 3000) ;
                }
            });
        }
    });
});

$(document).on('click', '.hapus_ovk', function(e) {
    e.preventDefault();
    $(document).find("div.text-danger").remove();

    var row_id  =   document.getElementById('angkatan').value ;
    var kandang =   document.getElementById('pilih_kandang').value ;
    var ovk     =   $(this).data('id') ;

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $.ajax({
        url: "{{ route('angkatanayam.destroy') }}",
        method: "PATCH",
        data: {
            row_id  :   row_id,
            ovk     :   ovk,
            key     :   'ovk'
        },
        success: function(data) {
            $.ajax({
                url: "{{ route('angkatanayam.index', ['key' => 'show_data']) }}",
                method: "GET",
                data: {
                    row_id  :   row_id,
                    kandang :   kandang,
                    tab     :   'ovk',
                },
                success: function(data) {
                    $("#show_data").empty().append(data);

                    $('.modal-backdrop').remove();
                    $('body').removeClass('modal-open');
                    document.getElementById('notif-success').innerHTML  =   'Hapus kartu stok ovk berhasil';
                    document.getElementById('notif-success').style      =   '';
                    $('#topbar-notification').fadeIn();
                    setTimeout(function() {
                        $('#topbar-notification').fadeOut();
                        document.getElementById('notif-error').style    =   'display: none';
                        document.getElementById('notif-success').style  =   'display: none';
                    }, 3000) ;
                }
            });
        }
    });
});

$(document).on('click', '.ubahdata', function(e) {
    e.preventDefault();
    $(document).find("div.text-danger").remove();

    var angkatan            =   $("#angkatan").val() ;
    var riwayat             =   $(this).data('id') ;
    var penempatan_kandang  =   $("#penempatan_kandang" + riwayat).val() ;
    var tanggal_pindah      =   $("#tanggal_pindah" + riwayat).val() ;

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $.ajax({
        url: "{{ route('angkatanayam.store') }}",
        method: "POST",
        data: {
            row_id              :   angkatan,
            riwayat             :   riwayat,
            penempatan_kandang  :   penempatan_kandang,
            tanggal_pindah      :   tanggal_pindah,
            key                 :   'ubahriwayat',
        },
        success: function(data) {
            $.ajax({
                url: "{{ route('angkatanayam.index', ['key' => 'show_data']) }}",
                method: "GET",
                data: {
                    row_id: angkatan,
                },
                success: function(data) {
                    $("#show_data").empty().append(data);

                    $('.modal-backdrop').remove();
                    $('body').removeClass('modal-open');
                    document.getElementById('notif-success').innerHTML  =   'Data riwayat berhasil diperbaharui';
                    document.getElementById('notif-success').style      =   '';
                    $('#topbar-notification').fadeIn();
                    setTimeout(function() {
                        $('#topbar-notification').fadeOut();
                        document.getElementById('notif-error').style    =   'display: none';
                        document.getElementById('notif-success').style  =   'display: none';
                    }, 3000) ;
                }
            });
        }
    });
});

$(document).on('click', '#input_home', function(e) {
    e.preventDefault();
    $(document).find("div.text-danger").remove();

    var angkatan            =   document.getElementById("angkatan").value ;
    var pilih_kandang       =   document.getElementById("pilih_kandang").value ;
    var strain_select       =   $("[name=strain_select]").val();
    console.log(strain_select+'<<strain-kandang>>'+pilih_kandang);
    // var strain              =   document.getElementById("strain").value ;
    // var input_strain        =   document.getElementById("input_strain").checked ;

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $.ajax({
        url: "{{ route('angkatanayam.store') }}",
        method: "POST",
        data: {
            angkatan        :   angkatan,
            pilih_kandang   :   pilih_kandang,
            strain_select   :   strain_select,
            // strain          :   strain,
            // input_strain    :   input_strain,
            key             :   'info',
        },
        success: function(data) {
            if (data.status == 0) {
                document.getElementById('notif-error').innerHTML    =   data.message;
                document.getElementById('notif-error').style        =   '';
            } else {
                document.getElementById('notif-success').innerHTML  =   data.message;
                document.getElementById('notif-success').style      =   '';
            }

            $('#topbar-notification').fadeIn();
            setTimeout(function() {
                $('#topbar-notification').fadeOut();
                document.getElementById('notif-error').style    =   'display: none';
                document.getElementById('notif-success').style  =   'display: none';
            }, 3000) ;

            if (data.status == 1) {
                $.ajax({
                    url: "{{ route('angkatanayam.index', ['key' => 'show_data']) }}",
                    method: "GET",
                    data: {
                        angkatan    : angkatan,
                        kandang     : pilih_kandang,
                    },
                    success: function(data) {
                        $("#show_data").empty().append(data);
                    }
                });
            }
        },
    });
});
</script>
@endsection

@section('content')
<div class="card">
    <div class="card-body">
        <div class="form-group">
            Pilih Angkatan
            <select name="angkatan" class="form-control select2" onchange="angkatan()"
                data-placeholder="Pilih Angkatan Ayam" id="angkatan">
                <option value=""></option>
                @foreach ($data as $row)
                <option value="{{ $row->id }}">Angkatan {{ $row->no }}</option>
                @endforeach
            </select>
        </div>

        <div id="show_kandang"></div>
    </div>
</div>

@endsection

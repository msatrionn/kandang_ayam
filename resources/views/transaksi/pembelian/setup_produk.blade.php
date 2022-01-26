@extends('layouts.main')

@section('title', 'Setup Produk Pembelian')

@section('footer')
<script>
$("#data_produk").load("{{ route('pembelian.setup', ['key' => 'data']) }}");
</script>

<script>
    $(document).on('click', '.ubahdata', function() {
        var id      =   $(this).data('id') ;

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            url: "{{ route('pembelian.storesetup') }}",
            method: "POST",
            data: {
                id  :   id,
            },
            success: function(data) {
                $("#data_produk").load("{{ route('pembelian.setup', ['key' => 'data']) }}");

                document.getElementById('notif-success').innerHTML  =   "Ubah data berhasil";
                document.getElementById('notif-success').style      =   '';
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
<div id="data_produk"></div>
@endsection

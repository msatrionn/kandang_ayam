@extends('layouts.main')

@section('title', 'Purchasing Order')

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
    var x = 1;
function addRow(){
    var row = '';
    row +=  '<div class="row-'+(x)+'">' ;
    row +=  '<div class="row">' ;
    row +=  '    <div class="col-5 pr-1">' ;
    row +=  '        <div class="form-group">' ;
    row +=  '           <select name="produk[]" data-placeholder="Pilih Produk" class="form-control">' ;
    row +=  '                <option value=""></option>' ;
    row +=  "                   {!! Produk::produk_purchase() !!}" ;
    row +=  '            </select>' ;
    row +=  '        </div>' ;
    row +=  '    </div>' ;
    row +=  '    <div class="col-3 px-1">' ;
    row +=  '        <div class="form-group">' ;
    row +=  '            <input type="number" name="harga_satuan[]" class="form-control px-1 harga_satuan" placeholder="Harga" autocomplete="off" data-politespace data-politespace-grouplength="3" data-politespace-delimiter="," data-politespace-decimal-mark="." step="0.01" data-politespace-reverse>' ;
    row +=  '        </div>' ;
    row +=  '    </div>' ;
    row +=  '    <div class="col-3 px-1">' ;
    row +=  '        <div class="form-group">' ;
    row +=  '            <input type="number" name="jumlah_purchase[]" class="form-control px-1 jumlah_purchase" placeholder="Qty" autocomplete="off" data-politespace data-politespace-grouplength="3" data-politespace-delimiter="," data-politespace-decimal-mark="." step="0.01" data-politespace-reverse>' ;
    row +=  '        </div>' ;
    row +=  '    </div>' ;
    row +=  '    <div class="col-auto pl-1">' ;
    row +=  '        <i onclick="deleteRow('+(x)+')" class="fa fa-trash cursor text-danger pt-2 mt-1"></i>' ;
    row +=  '    </div>' ;
    row +=  '</div>' ;

    row +=  '</div>' ;

    $('.data-loop').append(row);
    $(function () {
        $('select').each(function () {
            $(this).select2({
            theme: 'bootstrap4',
            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
            placeholder: $(this).data('placeholder'),
            allowClear: Boolean($(this).data('allow-clear')),
            closeOnSelect: !$(this).attr('multiple'),
            });
        });
    });

    jQuery( function(){
        jQuery( document ).trigger( "enhance" );
    });
    x++;
}

function deleteRow(rowid){
    $('.row-'+rowid).remove();
}
</script>

<script>
    $(".submit").on('click', function(){
        $(this).hide();
    })
</script>

<script>
    $('.details').load('{{ route("purchasing.show_index") }}')
    $("[name=q]").on('keyup',function () {
        var search=$(this).val();
        $('.details').load('{{ route("purchasing.show_index") }}?cari='+search)
    })
</script>

<script>
    function hitung() {
        var down_payment    =   document.getElementById("down_payment");
        var metode          =   document.getElementById("metode");

        if (down_payment.value > 0) {
            metode.style    =   'display: block';
        } else {
            metode.style    =   'display: none';
        }
    }
</script>

<script>
    $(document).ready(function() {
        $(document).on('click', '#check_kas', function() {
            var metode_select       =   $("#check_kas") ;
            var input               =   document.getElementById('metode_select');
            var metode              =   document.getElementById('metode_pembayaran');
            var tulis_kas           =   document.getElementById('tulis_pembayaran');

            if (metode_select.prop('checked')){
                input.style         =   'display: none' ;
                tulis_kas.style     =   'display: block' ;
                tulis_kas.value     =   '';
                $("#metode_pembayaran").val("").trigger("change");
            } else {
                input.style         =   'display: block' ;
                tulis_kas.style     =   'display: none' ;
                tulis_kas.value     =   '';
                $("#metode_pembayaran").val("").trigger("change");
            }
        });
    });
</script>

<script>
    $(document).ready(function() {
        $(document).on('click', '#check_supplier', function() {
            var input                   =   $("#check_supplier") ;
            var supplier_select         =   document.getElementById('supplier_select');
            var supplier_text           =   document.getElementById('supplier_text');
            var nama_supplier           =   document.getElementById('nama_supplier');
            var nomor_telepon           =   document.getElementById('nomor_telepon');
            var alamat                  =   document.getElementById('alamat');

            if (input.prop('checked')){
                supplier_select.style   =   'display: none' ;
                supplier_text.style     =   'display: block' ;
                nama_supplier.value     =   '';
                nomor_telepon.value     =   '';
                alamat.value            =   '';
                $("#supplier").val("").trigger("change");
            } else {
                supplier_select.style   =   'display: block' ;
                supplier_text.style     =   'display: none' ;
                nama_supplier.value     =   '';
                nomor_telepon.value     =   '';
                alamat.value            =   '';
                $("#supplier").val("").trigger("change");
            }
        });
    });
</script>

<script>
    $(".pagination").attr('class', 'pagination pagination-sm pt-2');
</script>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-5">
        <div class="card">
            <div class="card-header">
                Buat Purchasing Order
            </div>
            <div class="card-body">

                <form action="{{ route('purchasing.store') }}" method="POST">
                    @csrf

                    <div class="form-group">
                        <div class="form-group">
                            Kandang
                            <select name="kandang" id="kandang" class="form-control select2" data-width="100%"
                                data-placeholder="Pilih Kandang">
                                <option value=""></option>
                                <option value="ALL">Tanpa Kandang </option>
                                @foreach ($kandang as $id => $row)
                                <option value="{{ $id }}">{{ $row }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div id="supplier_select" style="{{ old('check_supplier') ? 'display: none' : '' }}">
                            Supplier
                            <select name="supplier" class="form-control" id="supplier"
                                data-placeholder="Pilih Supplier">
                                <option value=""></option>
                                @foreach ($supplier as $id => $nama)
                                <option value="{{ $id }}" {{ (old('supplier')==$id) ? 'selected' : '' }}>{{ $nama }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <label class="mt-2"><input id="check_supplier" name="check_supplier" {{ old('check_supplier')
                                ? 'checked' : '' }} type="checkbox"> Input supplier manual / Tidak ada di list</label>
                        <div id="supplier_text" style="{{ old('check_supplier') ? '' : 'display: none' }}">
                            <div class="form-group">
                                Nama Supplier
                                <input type="text" name="nama_supplier" class="form-control" id="nama_supplier"
                                    value="{{ old('nama_supplier') }}" placeholder="Tuliskan Nama Supplier"
                                    autocomplete="off">
                                @error("nama_supplier") <div class="small text-danger">{{ $message }}</div> @enderror
                            </div>

                            <div class="form-group">
                                Nomor Telepon
                                <input type="number" name="nomor_telepon" class="form-control" id="nomor_telepon"
                                    value="{{ old('nomor_telepon') }}" placeholder="Tuliskan Nomor Telepon"
                                    autocomplete="off">
                                @error("nomor_telepon") <div class="small text-danger">{{ $message }}</div> @enderror
                            </div>

                            <div class="form-group">
                                Alamat
                                <textarea name="alamat" id="alamat" class="form-control" placeholder="Tuliskan Alamat"
                                    rows="3">{{ old('alamat') }}</textarea>
                                @error("alamat") <div class="small text-danger">{{ $message }}</div> @enderror
                            </div>
                        </div>
                        @error("supplier") <div class="small text-danger">{{ $message }}</div> @enderror
                    </div>

                    <div class="data-loop">
                        <div class="row">
                            <div class="col-5 pr-1">
                                <div class="form-group">
                                    <select name="produk[]" data-placeholder="Pilih Produk" class="form-control">
                                        <option value=""></option>
                                        @foreach ($produk as $row)
                                        <option value="{{ $row->id }}" {{ old('produk')==$row->id ? 'selected' : ''
                                            }}>[{{ $row->tipeset->nama }}] {{ $row->nama }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-3 px-1">
                                <div class="form-group">
                                    <input type="number" name="harga_satuan[]" class="form-control px-1 harga_satuan"
                                        placeholder="Harga" autocomplete="off" data-politespace
                                        data-politespace-grouplength="3" data-politespace-delimiter=","
                                        data-politespace-decimal-mark="." step="0.01" data-politespace-reverse>
                                </div>
                            </div>
                            <div class="col-3 px-1">
                                <div class="form-group">
                                    <input type="number" name="jumlah_purchase[]"
                                        class="form-control px-1 jumlah_purchase" placeholder="Qty" autocomplete="off"
                                        data-politespace data-politespace-grouplength="3" data-politespace-delimiter=","
                                        data-politespace-decimal-mark="." step="0.01" data-politespace-reverse>
                                </div>
                            </div>
                            <div class="col-auto pl-1">
                                <i onclick="addRow()" class="fa fa-plus cursor text-success pt-2 mt-1"></i>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        Tanggal Purchase
                        <input type="date" name="tanggal_purchase" id="tanggal_purchase"
                            value="{{ old('tanggal_purchase') }}" class="form-control">
                        @error('tanggal_purchase') <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group">
                        <input type="checkbox" value="1" name="tax" {{ old('tax') ? 'checked' : '' }} id="tax">
                        <label for="tax">Include PPN</label>
                    </div>

                    <div class="form-group">
                        Down Payment
                        <input type="number" name="down_payment" class="form-control" value="{{ old('down_payment') }}"
                            onkeyup="hitung(); return false;" id="down_payment" placeholder="Tuliskan Down Payment"
                            autocomplete="off" data-politespace data-politespace-grouplength="3"
                            data-politespace-delimiter="," data-politespace-decimal-mark="." step="0.01"
                            data-politespace-reverse>
                        @error('down_payment') <div class="small text-danger">{{ $message }}</div> @enderror
                        <div class="small">Tulis Besaran Down Payment Apabila Dibayarkan</div>
                    </div>

                    <div class="form-group" style="{{ old('down_payment') ? '' : 'display: none' }}" id='metode'>
                        <div id="metode_select" style="{{ old('check_kas') ? 'display: none' : '' }}">
                            <select name="metode_pembayaran" id='metode_pembayaran'
                                data-placeholder="Pilih Metode Pembayaran" class="form-control">
                                <option value=""></option>
                                @foreach ($payment as $id => $row)
                                <option value="{{ $id }}" {{ old('metode_pembayaran')==$id ? 'selected' : '' }}>{{ $row
                                    }}</option>
                                @endforeach
                            </select>
                        </div>
                        <input type="text" style="{{ old('check_kas') ? '' : 'display: none' }}" name="tulis_pembayaran"
                            id="tulis_pembayaran" value="{{ old('tulis_pembayaran') }}" placeholder="Tulis Pembayaran"
                            autocomplete="off" class="form-control">
                        <label class="mt-2"><input id="check_kas" name="check_kas" {{ old('check_kas') ? 'checked' : ''
                                }} type="checkbox"> Input metode pembayaran manual / Tidak ada di list</label>
                        @error('metode_pembayaran') <div class="small text-danger">{{ $message }}</div> @enderror
                        @error('tulis_pembayaran') <div class="small text-danger">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-group">
                        Termin (Hari)
                        <input type="number" name="termin" class="form-control" value="{{ old('termin') }}" id="termin"
                            placeholder="Tuliskan Termin Dalam Hari" autocomplete="off" data-politespace
                            data-politespace-grouplength="3" data-politespace-delimiter=","
                            data-politespace-decimal-mark="." min="0" data-politespace-reverse>
                        @error('termin') <div class="small text-danger">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-group">
                        Keterangan
                        <textarea name="keterangan" id="keterangan" class="form-control"
                            placeholder="Tuliskan Keterangan" rows="3">{{ old('keterangan') }}</textarea>
                        @error('keterangan') <div class="small text-danger">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-group text-right">
                        <button type="submit" class="submit btn btn-primary">Submit</button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <div class="col-lg-7">

        <div class="card">
            <div class="card-header">Unduh Data Purchasing Order</div>
            <div class="card-body">
                <form action="{{ route('purchasing.pdf') }}" method="POST">
                    @csrf @method('patch')
                    <div class="row">
                        <div class="col-xl-5 col-6 pr-1">
                            <div class="form-group">
                                Mulai Report
                                <input type="date" name="mulai_report" class="form-control"
                                    value="{{ old('mulai_report') }}" id="mulai_report" autocomplete="off">
                                @error('mulai_report') <div class="small text-danger">{{ $message }}</div> @enderror
                            </div>
                        </div>
                        <div class="col-xl-5 col-6 pl-1 pl-xl-0 px-xl-1">
                            <div class="form-group">
                                Akhir Report
                                <input type="date" name="akhir_report" class="form-control"
                                    value="{{ old('akhir_report') }}" id="akhir_report"
                                    placeholder="Tuliskan Akhir Report" autocomplete="off">
                                @error('akhir_report') <div class="small text-danger">{{ $message }}</div> @enderror
                            </div>
                        </div>
                        <div class="col pl-xl-1">
                            <span class="d-none d-xl-block">&nbsp;</span>
                            <button class="btn btn-outline-success btn-block" type="submit"><i
                                    class="fa fa-file-excel-o"></i> Unduh</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                Daftar Purchasing Order
            </div>
            <div class="card-body">
                <form action="{{ route('purchasing.index') }}" method="get">
                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                Pencarian
                                <input type="text" name="q" class="form-control" placeholder="Cari..." value="{{ $q }}"
                                    autocomplete="off">
                            </div>

                        </div>
                    </div>
                </form>
                <div class="details"></div>
            </div>
        </div>
    </div>
    @endsection

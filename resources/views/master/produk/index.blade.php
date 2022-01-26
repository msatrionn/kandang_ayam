@extends('layouts.main')

@section('title', 'Daftar Produk Kandang')

@section('header')
<link rel="stylesheet" href="{{ asset('assets/vendor/select2/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/vendor/select2/select2-bootstrap4.min.css') }}">
@endsection

@section('footer')
<script>
    $(".pagination").attr('class', 'pagination pagination-sm pt-2');
</script>
<script src="{{ asset('assets/vendor/select2/select2.js') }}"></script>

<script>
    $(document).ready(function() {
        $(document).on('click', '#check_tipe', function() {
            var input                   =   $("#check_tipe") ;
            var tipe_select             =   document.getElementById('tipe_select');
            var tipe_produk               =   document.getElementById('tipe_produk');

            if (input.prop('checked')){
                tipe_select.style       =   'display: none' ;
                tipe_produk.style         =   'display: block' ;
                tipe_produk.value         =   '';
                $("#tipe").val("").trigger("change");
            } else {
                tipe_select.style       =   'display: block' ;
                tipe_produk.style         =   'display: none' ;
                tipe_produk.value         =   '';
                $("#tipe").val("").trigger("change");
            }
        });
    });


    $(document).ready(function() {
        $(document).on('click', '#check_satuan', function() {
            var input                   =   $("#check_satuan") ;
            var satuan_select           =   document.getElementById('satuan_select');
            var tulis_satuan             =   document.getElementById('tulis_satuan');

            if (input.prop('checked')){
                satuan_select.style     =   'display: none' ;
                tulis_satuan.style       =   'display: block' ;
                tulis_satuan.value       =   '';
                $("#satuan").val("").trigger("change");
            } else {
                satuan_select.style     =   'display: block' ;
                tulis_satuan.style       =   'display: none' ;
                tulis_satuan.value       =   '';
                $("#satuan").val("").trigger("change");
            }
        });
    });

</script>
<script>
    $('select[name=tipe]').on('change',function name(params) {
    var tipe = $(this).val()
    if (tipe==4) {
        $('#strain').html(`
    <div class="strain">
        <div class="form-group">
            Pilih strain
            <div id="strain_select">
                <select name="strain" class="form-control select2 strain" data-placeholder="Pilih Strain" onChange="strainChange()" >
                    <option value="">Pilih strain</option>
                    @foreach ($strain as $id => $nama)
                    <option value="{{ $id }}" {{ (old('strain')==$id) ? 'selected' : '' }}>{{ $nama }}
                    </option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
       `)
    }
    else{
        $('.strain').remove()
    }
   })
</script>
<script>
   $("[name=tipe]").on('change',function () {

    if ($(this).val()==4) {
        // ReadOnly=true
            $("[name=nama_produk]").attr('readOnly',true);
            $("[name=nama_produk]").val("DOC - ")
        }
    else{
        $("[name=nama_produk]").attr('readOnly',false);
            $("[name=nama_produk]").val("")
        }
   })

</script>
<script>
    function strainChange() {
          var strain_select=$('[name=strain]').val()
           $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            url: "{{ route('delivery.get_strain') }}",
            method: "GET",
            data: {
                slug: strain_select,
            },
            success: function(data) {
                $("[name=nama_produk]").val(`DOC - ${data.strain.nama}`);
            }
        });
          console.log(strain_select);
        //   $("[name=nama_produk]").val("DOC - " + strain_select)
    }
</script>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                Buat Produk
            </div>
            <div class="card-body">

                <form action="{{ route('produk.store') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        Nama Produk
                        <input type="text" name="nama_produk" value="{{ old('nama_produk') }}" class="form-control"
                            id="nama_produk" placeholder="Tuliskan Nama Produk" autocomplete="off">
                        @error("nama_produk") <div class="small text-danger">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-group">
                        Tipe Produk
                        <div id="tipe_select" style="{{ old('check_tipe') ? 'display:none' : '' }}">
                            <select name="tipe" class="form-control select2" id="tipe"
                                data-placeholder="Pilih Tipe Produk">
                                <option value=""></option>
                                @foreach ($tipe as $id => $nama)
                                <option value="{{ $id }}" {{ (old('tipe')==$id) ? 'selected' : '' }}>{{ $nama }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <input type="text" style="{{ old('check_tipe') ? '' : 'display: none' }}" name="tipe_produk"
                            id="tipe_produk" value="{{ old('tipe_produk') }}" placeholder="Tulis Tipe Produk"
                            autocomplete="off" class="form-control">
                        <label class="mt-2"><input id="check_tipe" name="check_tipe" {{ old('check_tipe') ? 'checked'
                                : '' }} type="checkbox"> Input tipe produk manual / Tidak ada di list</label>
                        @error("tipe") <div class="small text-danger">{{ $message }}</div> @enderror
                        @error("tipe_produk") <div class="small text-danger">{{ $message }}</div> @enderror
                    </div>

                    <div class="strain">
                        {{-- <div class="form-group">
                            Pilih strain
                            <div id="strain_select">
                                <select name="strain" class="form-control select2" data-placeholder="Pilih Strain">
                                    <option value="">Pilih strain</option>
                                    @foreach ($strain as $id => $nama)
                                    <option value="{{ $id }}" {{ (old('tipe')==$id) ? 'selected' : '' }}>{{ $nama }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div> --}}
                    </div>

                    <div id="strain"></div>

                    <div class="form-group">
                        Satuan
                        <div id="satuan_select" style="{{ old('check_satuan') ? 'display: none' : '' }}">
                            <select name="satuan" class="form-control" id="satuan" data-placeholder="Pilih Satuan">
                                <option value=""></option>
                                @foreach ($satuan as $id => $nama)
                                <option value="{{ $id }}" {{ (old('satuan')==$id) ? 'selected' : '' }}>{{ $nama }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <input type="text" style="{{ old('check_satuan') ? '' : 'display: none' }}" name="tulis_satuan"
                            id="tulis_satuan" value="{{ old('tulis_satuan') }}" placeholder="Tulis Satuan"
                            autocomplete="off" class="form-control">
                        <label class="mt-2"><input id="check_satuan" name="check_satuan" {{ old('check_satuan')
                                ? 'checked' : '' }} type="checkbox"> Input satuan manual / Tidak ada di list</label>
                        @error("satuan") <div class="small text-danger">{{ $message }}</div> @enderror
                        @error("tulis_satuan") <div class="small text-danger">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-group">
                        Limit Stock
                        <input type="number" name="limit_stock" class="form-control" value="{{ old('limit_stock') }}"
                            id="limit_stock" placeholder="Tuliskan Limit Stock" autocomplete="off">
                        @error('limit_stock') <div class="small text-danger">{{ $message }}</div> @enderror
                    </div>

                    {{-- <div class="form-group">
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
                        <div id="supplier_text" style="{{ old('check_supplier') ? '' : 'display: none' }}">
                            <label>Tambah Supplier</label>
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
                        <label class="mt-2"><input id="check_supplier" name="check_supplier" {{ old('check_supplier')
                                ? 'checked' : '' }} type="checkbox"> Input supplier manual / Tidak ada di list</label>
                        @error("supplier") <div class="small text-danger">{{ $message }}</div> @enderror
                    </div> --}}

                    <div class="form-group text-right">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                Daftar Produk Kandang
            </div>
            <div class="card-body">
                <form action="{{ route('produk.index') }}" method="get">
                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                Pencarian
                                <input type="text" name="q" class="form-control" placeholder="Cari..." value="{{ $q }}"
                                    autocomplete="off">
                            </div>

                        </div>
                        <div class="col-auto">
                            &nbsp;
                            <button type="submit" class="btn btn-block px-4 btn-primary">Cari</button>
                        </div>
                    </div>
                </form>

                @foreach ($data as $row)
                <div class="border rounded p-2 mb-2">
                    <div class="row">
                        <div class="col text-bold pr-1">
                            <span class="small text-primary text-bold border-right pr-1 mr-1">{{ $row->tipeset->nama
                                }}</span>{{ $row->nama }}
                        </div>
                        <div class="col-auto pl-1">{{ $row->tipesatuan->nama }}</div>
                    </div>
                    <div class="row">
                        <div class="col pr-1"> @if ($row->stocklimit) Limit : {{ $row->stocklimit }} @endif</div>
                        <div class="col-auto pl-1">
                            <form action="{{ route('produk.destroy') }}" method="POST">
                                @csrf @method('delete') <input type="hidden" name="x_code" value="{{ $row->id }}"">
                                <button type=" button" class="btn btn-link text-primary p-0" data-toggle="modal"
                                    data-target="#modal{{ $row->id }}">
                                <i class="icon-note"></i>
                                </button>
                                <button type="submit" class="btn btn-link text-danger p-0">
                                    <i class="icon-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="modal fade" id="modal{{ $row->id }}" tabindex="-1"
                    aria-labelledby="modal{{ $row->id }}Label" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="modal{{ $row->id }}Label">Ubah Produk</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <form action="{{ route('produk.update') }}" method="POST">
                                @csrf @method('patch') <input type="hidden" name="x_code" value="{{ $row->id }}"">
                                <div class=" modal-body">
                                <div class="form-group">
                                    Nama Produk
                                    <input type="text" name="nama_produk" value="{{ $row->nama }}" class="form-control"
                                        placeholder="Tuliskan Nama Produk" autocomplete="off">
                                </div>

                                <div class="form-group">
                                    Tipe Produk
                                    <select name="tipe" class="form-control" data-placeholder="Pilih Tipe Produk">
                                        <option value=""></option>
                                        @foreach ($tipe as $id => $nama)
                                        <option value="{{ $id }}" {{ ($row->tipe == $id) ? 'selected' : '' }}>{{ $nama
                                            }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group">
                                    Satuan
                                    <select name="satuan" class="form-control" data-placeholder="Pilih Satuan</">
                                        <option value="">option>
                                            @foreach ($satuan as $id => $nama)
                                        <option value="{{ $id }}" {{ ($row->satuan == $id) ? 'selected' : '' }}>{{ $nama
                                            }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group">
                                    Limit Stock
                                    <input type="number" name="limit_stock" class="form-control"
                                        value="{{ $row->stocklimit }}" placeholder="Tuliskan Limit Stock"
                                        autocomplete="off">
                                </div>

                                {{-- <div class="form-group">
                                    Supplier
                                    <select name="supplier" class="form-control" data-placeholder="Pilih Supplier</">
                                        <option value="">option>
                                            @foreach ($supplier as $id => $nama)
                                        <option value="{{ $id }}" {{ ($row->supplier_id == $id) ? 'selected' : '' }}>{{
                                            $nama }}</option>
                                        @endforeach
                                    </select>
                                </div> --}}
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Save changes</button>
                        </div>
                        </form>
                    </div>
                </div>
            </div>
            @endforeach

            {{ $data->appends($_GET)->onEachSide(0)->links() }}
        </div>
    </div>
</div>
</div>
@endsection

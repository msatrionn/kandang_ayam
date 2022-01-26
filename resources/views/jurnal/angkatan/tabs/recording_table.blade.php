<div id="notif"></div>
<table class="table table-sm table-bordered tabeles">
    <thead>
        <tr>
            <th class="text-center border sticky-col sticky-head first-col" style="z-index: 98" rowspan="2">
                Tanggal</th>
            <th class="text-center sticky-col sticky-head second-col" style="z-index: 98" colspan="2">Umur</th>
            <th class="text-center sticky-head" colspan="4">Populasi Ayam (Ekor)</th>
            <th class="text-center sticky-head" colspan="7">Konsumsi Pakan</th>
            <th class="text-center sticky-head" rowspan="2">Jenis Pakan</th>
            <th class="text-center sticky-head" colspan="2">BB</th>
            <th class="text-center sticky-head" rowspan="2">OVK</th>
            <th class="text-center sticky-head" rowspan="2">Keterangan</th>
        </tr>
        <tr>
            <th class="text-center sticky-heading sticky-col sticky-head hari-col" style="z-index: 98">Hari</th>
            <th class="text-center sticky-heading sticky-col sticky-head minggu-col" style="z-index: 98">Minggu
            </th>
            <th class="text-center sticky-heading sticky-head"> Mati</th>
            <th class="text-center sticky-heading sticky-head">Afkir</th>
            <th class="text-center sticky-heading sticky-head">Panen</th>
            <th class="text-center sticky-heading sticky-head">Hidup</th>
            <th class="text-center sticky-heading sticky-head">Datang</th>
            <th class="text-center sticky-heading sticky-head">Kg/Hari</th>
            <th class="text-center sticky-heading sticky-head">Stock</th>
            <th class="text-center sticky-heading sticky-head">Gr/Ekor/Hari</th>
            <th class="text-center sticky-heading sticky-head">Standar Gram/Hari</th>
            <th class="text-center sticky-heading sticky-head">Kumulatif</th>
            <th class="text-center sticky-heading sticky-head">Standar Global</th>
            <th class="text-center sticky-heading sticky-head">Gr/Ekor</th>
            <th class="text-center sticky-heading sticky-head">Standar</th>
        </tr>
    </thead>
    <tbody>
        @php
        $hidup = 0;
        $mati = 0;
        $afkir = 0;
        $panen = 0;
        $masuk = 0;
        $keluar = 0;
        $kumulatif = 0;
        $timbang = 0;
        @endphp
        @for ($i = 1; $i <= 91; $i++) @php $mati=Populasi::view_record($kandang->id, $i)->populasi_mati ?? 0 ;
            $afkir = Populasi::view_record($kandang->id, $i)->populasi_afkir ?? 0 ;
            $panen = Populasi::view_record($kandang->id, $i)->populasi_panen ?? 0 ;

            $hidup += ($mati + $afkir + $panen) ;

            $masuk += Kartu::view_pakan($kandang->id, $i, 'masuk') ?? 0 ;
            $keluar += Kartu::view_pakan($kandang->id, $i, 'keluar') ?? 0 ;

            $gram_ekor = Kartu::view_pakan($kandang->id, $i, 'keluar') ? round(Kartu::view_pakan($kandang->id,
            $i, 'keluar') / ($kandang->populasi - $hidup), 2) : 0;
            $kumulatif += $gram_ekor ;
            @endphp
            <tr>
                <td class="sticky-col first-col" style="z-index: 97">{{
                    Tanggal::date(Carbon\Carbon::parse($kandang->tanggal)->addDays(($i - 1))) }}
                </td>
                <td class="text-center sticky-col hari-col" style="z-index: 97">{{ $i }}</td>
                @if ($i%7 == 1)
                <td class="text-center sticky-col minggu-col" style="z-index: 97" rowspan="7">{{ ($i%7 == 1) ?
                    round($i / 7) + 1 : '' }}</td>
                @endif
                <td class="text-center">
                    @if (Populasi::view_record($kandang->id, $i))
                    <input type="number" name="mati" id="" class="form-control col-md-12"
                        value="{{ Populasi::view_record($kandang->id, $i) ? $mati : '' }}"
                        style="text-align:center;width:50px"
                        data-id_mati="{{ Populasi::where('hari',$i)->first()->id ?? 0 }}"
                        data-tanggal="{{ $kandang->tanggal }}" data-hari={{ $i }}>
                    @else
                    <input type="number" name="mati" id="" class="form-control alert-danger col-md-12"
                        value="{{ Populasi::view_record($kandang->id, $i) ? $mati : '' }}"
                        style="text-align:center;width:50px"
                        data-id_mati="{{ Populasi::where('hari',$i)->first()->id ?? 0 }}"
                        data-tanggal="{{ $kandang->tanggal }}" data-hari={{ $i }}>
                    @endif
                </td>
                <td class="text-center">
                    @if (Populasi::view_record($kandang->id, $i))
                    <input type="number" name="afkir" id="" class="form-control col-md-12"
                        value="{{ Populasi::view_record($kandang->id, $i) ? $afkir : '' }}"
                        style="text-align:center;width:50px" data-tanggal="{{ $kandang->tanggal }}" data-hari={{ $i }}
                        data-id_afkir="{{ Populasi::where('hari',$i)->first()->id ?? 0 }}">
                    @else
                    <input type="number" name="afkir" id="" class="form-control alert-danger col-md-12"
                        value="{{ Populasi::view_record($kandang->id, $i) ? $afkir : '' }}"
                        style="text-align:center;width:50px" data-tanggal="{{ $kandang->tanggal }}" data-hari={{ $i }}
                        data-id_afkir="{{ Populasi::where('hari',$i)->first()->id ?? 0 }}">
                    @endif
                </td>
                <td class="text-center">
                    @if (Populasi::view_record($kandang->id, $i))
                    <input type="number" name="panen" id="" class="form-control col-md-12"
                        value="{{ Populasi::view_record($kandang->id, $i) ? $panen : '' }}"
                        style="text-align:center;width:50px" data-tanggal="{{ $kandang->tanggal }}" data-hari={{ $i }}
                        data-id_panen="{{ Populasi::where('hari',$i)->first()->id ?? 0 }}">
                    @else
                    <input type="number" name="panen" id="" class="form-control alert-danger col-md-12"
                        value="{{ Populasi::view_record($kandang->id, $i) ? $panen : '' }}"
                        style="text-align:center;width:50px" data-tanggal="{{ $kandang->tanggal }}" data-hari={{ $i }}
                        data-id_panen="{{ Populasi::where('hari',$i)->first()->id ?? 0 }}">
                    @endif
                </td>
                <td class="text-center table-secondary">{{ Populasi::view_record($kandang->id, $i) ?
                    ($kandang->populasi - $hidup) : '' }}</td>
                <td class="text-center">{{ Kartu::where('hari',$i)->where('tipekartu','pakan')
                    ->orderBy('masuk','desc')->take(1)->first()->edited }}
                    @if (Kartu::where('hari',$i)->where('tipekartu','pakan')
                    ->orderBy('masuk','desc')->take(1)->first()->edited == 'sudah diedit')
                    <input type="number" name="datang" class="btn btn-warning" style="width: 100px;" data-toggle="modal"
                        data-target="#exampleModal" id="datang" value="{{
                            (Kartu::view_pakan($kandang->id, $i,
                            'masuk') > 0) ?
                            DB::table('kartustok')->where('hari',
                    $i)
                    ->where('tipekartu', 'pakan')->orderBy('masuk', 'desc')
                    ->take(1)->first()->masuk : '' }}"
                        data-id_datang="{{ Kartu::where('hari',$i)->where('tipekartu','pakan')->first()->id ?? 0 }}"
                        data-hari_datang={{ $i ?? 0 }}
                        data-jenis_pakan="{{ substr(Kartu::view_pakan($kandang->id, $i, 'pakan'), 0, -2) }}"
                        data-jenis_pakanid="{{ substr(Kartu::view_pakanid($kandang->id, $i, 'pakan'), 0, -2) }}"
                        data-tanggal_hidden="{{
                        Tanggal::date(Carbon\Carbon::parse($kandang->tanggal)->addDays(($i - 1))) }}">

                    @if (Kartu::where('hari',$i)->where('tipekartu','pakan')->first()->edited == 'sudah diedit')
                    <i class="fa fa-check"></i>
                    @else
                    <i class="fa fa-window-close-o"></i>
                    @endif
                    @else

                    <input type="number" name="datang" class="btn btn-danger" style="width: 100px;" data-toggle="modal"
                        data-target="#exampleModal" id="datang" value="{{
                                            (Kartu::view_pakan($kandang->id, $i,
                                            'masuk') > 0) ?
                                       DB::table('kartustok')->where('hari',
                                    $i)
                                    ->where('tipekartu', 'pakan')->orderBy('masuk', 'desc')
                                    ->take(1)->first()->masuk: '' }}"
                        data-id_datang="{{ Kartu::where('hari',$i)->where('tipekartu','pakan')->first()->id ?? 0 }}"
                        data-hari_datang={{ $i ?? 0 }}
                        data-jenis_pakan="{{ substr(Kartu::view_pakan($kandang->id, $i, 'pakan'), 0, -2) }}"
                        data-jenis_pakanid="{{ substr(Kartu::view_pakanid($kandang->id, $i, 'pakan'), 0, -2) }}"
                        data-tanggal_hidden="{{
                            Tanggal::date(Carbon\Carbon::parse($kandang->tanggal)->addDays(($i - 1))) }}">

                    @if (Kartu::where('hari',$i)->where('tipekartu','pakan')->first()->edited == 'sudah diedit')
                    <i class="fa fa-check"></i>
                    @else
                    <i class="fa fa-window-close-o"></i>
                    @endif
                    @endif

                </td>
                <td class="text-center">
                    @if (!empty(Kartu::view_pakan($kandang->id, $i,
                    'keluar')))
                    <input type="number" name="keluar" class="form-control" id="keluar" value="{{ (Kartu::view_pakan($kandang->id, $i, 'keluar') > 0) ?
                   DB::table('kartustok')->where('hari',
                    $i)
                    ->where('tipekartu', 'pakan')->orderBy('keluar', 'desc')
                    ->take(1)->first()->keluar : '' }}"
                        data-id_keluar="{{ Kartu::where('hari',$i)->first()->id ?? 0 }}" data-hari={{ $i ?? 0}}
                        data-kandang="{{ $kandang->id }}">
                    @else
                    <input type="number" name="keluar" class="form-control alert-danger" id="" value="{{ (Kartu::view_pakan($kandang->id, $i, 'keluar') > 0) ?
                   DB::table('kartustok')->where('hari',
                    $i)
                    ->where('tipekartu', 'pakan')->first()->keluar : '' }}"
                        data-id_keluar="{{ Kartu::where('hari',$i)->first()->id ?? 0 }}" data-hari={{ $i ?? 0 }}
                        data-kandang="{{ $kandang->id }}">
                    @endif
                </td>
                <td class="text-center">{{ (Kartu::view_pakan($kandang->id, $i, 'masuk') +
                    Kartu::view_pakan($kandang->id, $i, 'keluar')) ? ($masuk - $keluar) : '' }}</td>
                <td class="text-center table-secondary">{{ ($gram_ekor > 0) ? $gram_ekor : '' }}</td>
                <td class="text-center">{{ Strain::strain_angka('gram', $kandang->strain_id, $i) }}</td>
                <td class="text-center table-secondary">{{ ($gram_ekor > 0) ? $kumulatif : '' }}</td>
                <td class="text-center">{{ Strain::strain_angka('global', $kandang->strain_id, $i) }}</td>
                <td class="text-center">{{ substr(Kartu::view_pakan($kandang->id, $i, 'pakan'), 0, -2) }}
                </td>
                <td class="text-center">{{ (Timbang::hasil_timbang($kandang->id, $i) > 0) ?
                    Timbang::hasil_timbang($kandang->id, $i) : '' }}</td>

                <td class="text-center">{{ Strain::strain_angka('bb', $kandang->strain_id, $i) }}</td>
                <td class="text-center">{{ Kartu::where('hari',$i)->where('tipekartu','ovk')->first()->edited }}
                    @if (Kartu::where('hari',$i)->where('tipekartu','ovk')->first()->edited == 'sudah diedit')
                    {{-- <select name="ovk" class="form-control" style="text-align:center;width:120px"
                        data-id_ovk="{{ Kartu::where('hari',$i)->where('tipekartu','ovk')->first()->id ?? 0 }}">
                        <option value="{{ substr(Kartu::view_ovk($kandang->id, $i), 0, -2) }}" selected>
                            {{ substr(Kartu::view_ovk($kandang->id, $i), 0, -2) }}</option>
                        @foreach ($ovk as $row)
                        <option value="{{ $row->produk_id }}">{{ $row->produk->nama }}</option>
                        @endforeach
                    </select> --}}
                    <input type="text" class="btn btn-success" id="ovk" name="ovk" style="width: 150px;"
                        data-toggle="modal" data-target="#exampleModalOvk"
                        value="{{ substr(Kartu::view_ovk($kandang->id, $i), 0, -2) }}"
                        data-jenis_ovk="{{ substr(Kartu::view_ovk($kandang->id, $i), 0, -2) }}"
                        data-jumlahovk="{{ Kartu::where('hari',$i)->where('tipekartu','ovk')->first()->masuk ?? 0 }}"
                        data-jumlahovk_keluar="{{ Kartu::where('hari',$i)->where('tipekartu','ovk')->first()->keluar ?? 0 }}"
                        data-hari_ovk={{ $i }}
                        data-jenis_ovkid="{{ substr(Kartu::view_ovkid($kandang->id, $i), 0, -2) }}"
                        data-tanggal_hidden_ovk="{{
                        Tanggal::date(Carbon\Carbon::parse($kandang->tanggal)->addDays(($i - 1))) }}">

                    @if (Kartu::where('hari',$i)->where('tipekartu','ovk')->first()->edited == 'sudah diedit')
                    <i class="fa fa-check"></i>
                    @else
                    <i class="fa fa-window-close-o"></i>
                    @endif
                    @else
                    <input type="text" class="btn btn-danger" id="ovk" name="ovk" style="width: 150px;"
                        data-toggle="modal" data-target="#exampleModalOvk"
                        value="{{ substr(Kartu::view_ovk($kandang->id, $i), 0, -2) }}"
                        data-jenis_ovk="{{ substr(Kartu::view_ovk($kandang->id, $i), 0, -2) }}"
                        data-jumlahovk="{{ Kartu::where('hari',$i)->where('tipekartu','ovk')->first()->masuk ?? 0 }} "
                        data-jumlahovk_keluar="{{ Kartu::where('hari',$i)->where('tipekartu','ovk')->first()->keluar ?? 0 }}"
                        data-hari_ovk={{ $i }}
                        data-jenis_ovkid="{{ substr(Kartu::view_ovkid($kandang->id, $i), 0, -2) }}"
                        data-tanggal_hidden_ovk="{{
                            Tanggal::date(Carbon\Carbon::parse($kandang->tanggal)->addDays(($i - 1))) }}">

                    @if (Kartu::where('hari',$i)->where('tipekartu','ovk')->first()->edited == 'sudah diedit')
                    <i class="fa fa-check"></i>
                    @else
                    <i class="fa fa-window-close-o"></i>
                    @endif
                    @endif

                    {{-- <select name="ovk" class="form-control" style="text-align:center;width:120px"
                        data-id_ovk="{{ Kartu::where('hari',$i)->where('tipekartu','ovk')->first()->id ?? 0 }}">
                        <option value="{{ substr(Kartu::view_ovk($kandang->id, $i), 0, -2) }}" selected>
                            {{ substr(Kartu::view_ovk($kandang->id, $i), 0, -2) }}</option> --}}
                        {{-- <option value="{{ $row->produk_id }}">{{ $row->produk->nama }}</option> --}}
                        {{--
                    </select> --}}

                </td>
                <td>
                    {{ Catatan::where('riwayat_id', $kandang->id)->where('hari', $i)->first()->data_catatan ??
                    '' }}
                    <div class="small"> {{ Catatan::where('riwayat_id', $kandang->id)->where('hari',
                        $i)->first()->user->name ?? '' }}</div>
                </td>
            </tr>
            @if ($i%7 == 0)
            @php
            $fcr = Timbang::timbang_mingguan($kandang->id, (7 * round($i / 7))) > 0 ? ($kumulatif /
            Timbang::timbang_mingguan($kandang->id, (7 * round($i / 7)))) : 0 ;
            @endphp
            <tr class="bg-light">
                <td class="font-weight-bold sticky-col first-col" style="z-index: 97" colspan="3">Sub Total</td>
                <td class="text-center font-weight-bold">{{ Populasi::sub_total($kandang->id, (7 * round($i /
                    7)), 'mati') }}</td>
                <td class="text-center font-weight-bold">{{ Populasi::sub_total($kandang->id, (7 * round($i /
                    7)), 'afkir') }}</td>
                <td class="text-center font-weight-bold">{{ Populasi::sub_total($kandang->id, (7 * round($i /
                    7)), 'panen') }}</td>
                <td class="text-center font-weight-bold">{{ ($kandang->populasi - $hidup) }}</td>
                <td class="text-center font-weight-bold">{{ $masuk }}</td>
                <td class="text-center font-weight-bold">{{ $keluar }}</td>
                <td class="text-center font-weight-bold"></td>
                <td class="text-center font-weight-bold">{{ $kumulatif }}</td>
                <td class="text-center"></td>
                <td class="text-center font-weight-bold">{{ $kumulatif }}</td>
                <td class="font-weight-bold" colspan="2">FCR : {{ number_format(($fcr * 100), 2) }} %</td>
                <td class="text-center font-weight-bold">{{ Timbang::timbang_mingguan($kandang->id, (7 *
                    round($i / 7))) }}</td>
                <td class="font-weight-bold" colspan="2">Unifomity : {{ Timbang::unifomity($kandang->id, (7 *
                    round($i / 7))) }}</td>
                <td class="text-center font-weight-bold"></td>
            </tr>
            @endif
            <!-- Modal pakan -->
            <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="">Tanggal </label>
                                <div class="tanggal_datang">

                                </div>
                            </div>
                            <div class="form-group">
                                <label for="">Jenis Pakan</label>
                                <input type="hidden" name="id_kartu" id="">
                                <input type="hidden" name="hari" id="">
                                <select name="jenis_pakan_select"
                                    class="form-control @error('pakan_select') is-invalid @enderror select2pakan"
                                    required>
                                </select>
                                <div class="error_handling"></div>
                            </div>
                            <div class="form-group">
                                <label for="">Jumlah</label>
                                <input type="hidden" name="jumlah_awal_pakan" id="jumlah_awal_pakan" value=""
                                    class="form-control">
                                <input type="text" name="jumlah_pakan" id="jumlah_pakan" value="" class="form-control">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary " id="submit_datang">Save changes</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal ovk -->
            <div class="modal fade" id="exampleModalOvk" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Modal Ovk</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="">Tanggal</label>
                                <input type="text" name="tanggal_kandang_ovk" class="form-control tanggal_kandang_ovk"
                                    value="{{ Tanggal::date($kandang->tanggal) }}" readonly>
                            </div>
                            <div class="form-group">
                                <label for="">Jenis Ovk</label>
                                <input type="hidden" name="id_kartu" id="">
                                <input type="hidden" name="hari" id="">
                                <select name="jenis_ovk_select" class="form-control select2ovk" required>
                                    {{-- <option value='{{ substr(Kartu::view_ovk($kandang->id, $i), 0, -2) }}'
                                        selected>{{
                                        substr(Kartu::view_ovk($kandang->id, $i), 0, -2) }} </option>
                                    @foreach ($ovk as $row)
                                    <option value='{{ $row->produk_id }}'>{{ $row->produk->nama }}({{$row->stock_sisa}})
                                    </option>
                                    @endforeach --}}
                                </select>
                                <div class="error_handling"></div>
                            </div>
                            <div class="form-group">
                                <label for="">Jumlah Masuk</label>
                                <input type="hidden" name="jumlah_awal_ovk" id="jumlah_awal_ovk" value=""
                                    class="form-control">
                                <input type="text" name="jumlah_ovk" id="jumlah_ovk" value="" class="form-control">
                                <label for="">Jumlah Keluar</label>
                                <input type="text" name="jumlah_ovk_keluar" id="jumlah_ovk_keluar" value=""
                                    class="form-control">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-success" id="submit_datang_ovk">Save changes</button>
                        </div>
                    </div>
                </div>
            </div>
            @endfor
    </tbody>
</table>
<link rel="stylesheet" href="{{ asset('assets/vendor/select2/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/vendor/select2/select2-bootstrap4.min.css') }}">
<script src="{{ asset('assets/vendor/select2/select2.js') }}"></script>
<script>
</script>
<script>
    $.ajaxSetup({
    headers: {
    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
    });
    // console.log($("#mati").val());
    $("[name=mati]").on("change",function(){
    var mati= $(this).val();
    var id_mati = $(this).attr('data-id_mati');
    var kandang=$("[name=kandang]").val()
    var angkatan = $("#angkatan").val();
    var hari=$(this).attr('data-hari')
    var tanggal=$(this).attr('data-tanggal')
    // console.log(id_mati + "mati:" + mati)
    $.ajax({
    url:"{{ route('edit.record') }}",
    method:"POST",
    data:{
    mati:mati,
    key:'mati',
    id_mati:id_mati,
    kandang:kandang,
    angkatan:angkatan,
    hari_populasi:hari,
    tanggal:tanggal,
    }
    ,
    success:function(data){
    $('#table-record').load(`{{ url('/jurnal-angkatan/table/${kandang}') }}`)
    // $('#topbar-notification').fadeIn();

    $('#notif').html("<div class='alert alert-success' role='alert'> Berhasil diubah</div>")
    // console.log(data);
    }
    })
    })
</script>


<script>
    $.ajaxSetup({
    headers: {
    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
    });
    // console.log($("#mati").val());
    $("[name=afkir]").on("change",function(){
    var afkir= $(this).val();
    var id_afkir = $(this).attr('data-id_afkir');
    var kandang=$("[name=kandang]").val()
    var angkatan = $("#angkatan").val();
    var hari=$(this).attr('data-hari')
    var tanggal=$(this).attr('data-tanggal')
    // console.log(id_afkir + "afkir:" + afkir)
    $.ajax({
    url:"{{ route('edit.record') }}",
    method:"POST",
    data:{
    afkir:afkir,
    id_afkir:id_afkir,
    key:'afkir',
    kandang:kandang,
    angkatan:angkatan,
    hari_populasi:hari,
    tanggal:tanggal,
    }
    ,
    success:function(data){
    $('#table-record').load(`{{ url('/jurnal-angkatan/table/${kandang}') }}`)
   $('#notif').html("<div class='alert alert-success' role='alert'> Berhasil diubah</div>")
    // console.log(data);
    }
    })
    })
</script>
<script>
    $.ajaxSetup({
    headers: {
    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
    });
    // console.log($("#mati").val());
    $("[name=panen]").on("change",function(){
    var panen= $(this).val();
    var id_panen = $(this).attr('data-id_panen');
    var kandang=$("[name=kandang]").val()
    var angkatan = $("#angkatan").val();
    var hari=$(this).attr('data-hari')
    var tanggal=$(this).attr('data-tanggal')
    console.log(id_panen + "panen:" + panen)
    $.ajax({
    url:"{{ route('edit.record') }}",
    method:"POST",
    data:{
    panen:panen,
    id_panen:id_panen,
    key:'panen',
    kandang:kandang,
    angkatan:angkatan,
    hari_populasi:hari,
    tanggal:tanggal,
    }
    ,
    success:function(data){
    $('#table-record').load(`{{ url('/jurnal-angkatan/table/${kandang}') }}`)
   $('#notif').html("<div class='alert alert-success' role='alert'> Berhasil diubah</div>")
    // console.log(data);
    }
    })
    })
</script>

<script>
    $.ajaxSetup({
    headers: {
    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
    });
    $("[name=datang]").on("click",function(){
    var tgl=$(this).attr("data-tanggal_hidden")
    $(".tanggal_datang").html(tgl)
    console.log('====================================');
    console.log(tgl);
    console.log('====================================');
    var datang= $(this).val();
    var jenis_pakan= $(this).attr("data-jenis_pakan");
    var jenis_pakanid= $(this).attr("data-jenis_pakanid");
    var jumlah_pakan=$("[name=jumlah_pakan]").val(datang)
    var jumlah_awal_pakan=$("[name=jumlah_awal_pakan]").val(datang)
    var hari=$(this).attr('data-hari_datang')
    $("[name=hari]").val(hari)

    $("[name=jenis_pakan_select]").val(jenis_pakan);
    $("[name=select2pakan]").val(jenis_pakanid);
    console.log(jenis_pakan);
    console.log(jenis_pakanid);
    // var $newOption = $("<option selected='selected'></option>").val(`${jenis_pakanid}`).text(`${jenis_pakan}`)
    var $newOption = $(`<option value='${jenis_pakanid}' selected>${jenis_pakan}</option>@foreach ($pakan as $row)<option value='{{ $row->produk_id }}'>{{ $row->produk->nama }}({{ $row->stock_sisa}})</option>@endforeach`)

    $(".select2pakan").html($newOption).trigger('change');
    // $(`#select2 option[value=value]`).html(`<option value="${jenis_pakanid}" selected>${jenis_pakan}</option>`);
    var id_datang = $(this).attr('data-id_datang');
    var kandang=$("[name=kandang]").val()
    $("[name=id_kartu]").val(id_datang)
    })


</script>
<script>
    $.ajaxSetup({
    headers: {
    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
    });
    $("#submit_datang").on("click", function () {
        var jumlah_awal=$("[name=jumlah_awal_pakan]").val();
        var jumlah=$("[name=jumlah_pakan]").val();
        var jenis = $("[name=jenis_pakan_select]").val();
        var id_datang = $("[name=id_kartu]").val();
        var hari= $("[name=hari]").val();
        var kandang=$("[name=kandang]").val()
        console.log(jumlah +'jenis'+ jenis +'id'+ hari);
        if (!jenis) {
            $(".error_handling").html("<div class='alert alert-danger errored_info'>Harap isi jenis pakan</div>")
        }
        else{
            $('.errored_info').remove()
        }
        $.ajax({
        url:"{{ route('edit.record') }}",
        method:"POST",
        data:{
        id_datang:'id_datang',
        jenis:jenis,
        hari_datang:hari,
        kandang:kandang,
        jumlah_awal:jumlah_awal,
        jumlah:jumlah
        },
        success:function(data){
        $('#table-record').load(`{{ url('/jurnal-angkatan/table/${kandang}') }}`)
        $('#notif').html("<div class='alert alert-success' role='alert'> Berhasil diubah</div>")
        $("#exampleModal .close").click()
        console.log(data);
        }
        })

    })
</script>
<script>
    $.ajaxSetup({
    headers: {
    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
    });
    // console.log($("[name=keluar]").val());
    $("[name=keluar]").on("change",function(){
    var keluar= $(this).val();
    var hari= $(this).attr('data-hari');
    var id_keluar = $(this).attr('data-id_keluar');
    var kandang=$("[name=kandang]").val()
    console.log(hari + "keluar:" + keluar)
    $.ajax({
    url:"{{ route('edit.record') }}",
    method:"POST",
    data:{
    keluar:keluar,
    kandang:kandang,
    hari_keluar:hari,
    id_keluar:'id_keluar'
    }
    ,
    success:function(data){
    $('#table-record').load(`{{ url('/jurnal-angkatan/table/${kandang}') }}`)
    $('#notif').html("<div class='alert alert-success' role='alert'> Berhasil diubah</div>")
    }
    })
    })
</script>
<script>
    $.ajaxSetup({
    headers: {
    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
    });
    console.log($("#pakan").val());
    $("[name=pakan]").on("change",function(){
    var pakan= $(this).val();
    var id_pakan = $(this).attr('data-id_pakan');
    var kandang=$("[name=kandang]").val()
    console.log(id_pakan + "pakan:" + pakan)
    $.ajax({
    url:"{{ route('edit.record') }}",
    method:"POST",
    data:{
    pakan:pakan,
    id_pakan:id_pakan
    }
    ,
    success:function(data){
        $('#notif').html("<div class='alert alert-success' role='alert'> Berhasil diubah</div>")
    $('#table-record').load(`{{ url('/jurnal-angkatan/table/${kandang}') }}`)
    console.log(data);
    }
    })
    })
</script>

<script>
    $.ajaxSetup({
    headers: {
    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
    });
    $("[name=ovk]").on("click",function(){
    var tgl=$(this).attr("data-tanggal_hidden_ovk")
    // console.log('====================================');
    // console.log(tgl);
    // console.log('====================================');
    $("[name=tanggal_kandang_ovk]").val(tgl)
    var ovk= $(this).attr('data-jumlahovk');
    var ovk_keluar= $(this).attr('data-jumlahovk_keluar');
    var jenis_ovk= $(this).attr("data-jenis_ovk");
    var jenis_ovkid= $(this).attr("data-jenis_ovkid");
    var jumlah_ovk=$("[name=jumlah_ovk]").val(ovk)
    var jumlah_ovk_keluar=$("[name=jumlah_ovk_keluar]").val(ovk_keluar)
    var jumlah_awal_ovk=$("[name=jumlah_awal_ovk]").val(ovk)
    var hari=$(this).attr('data-hari_ovk')
    $("[name=hari]").val(hari)

    $("[name=jenis_ovk_select]").val(jenis_ovk);
    $("[name=select2ovk]").val(jenis_ovkid);
    // console.log(jenis_ovk);
    // console.log(jenis_ovkid);
    var $newOption = $(`<option value='${jenis_ovkid}' selected>${jenis_ovk} </option>@foreach ($ovk as $row)<option value='{{ $row->produk_id }}'>{{ $row->produk->nama }}({{ $row->stock_sisa}})</option>@endforeach`)

    $(".select2ovk").html($newOption).trigger('change');
    // $(`#select2 option[value=value]`).html(`<option value="${jenis_ovkid}" selected>${jenis_ovk}</option>`);
    var id_datang = $(this).attr('data-id_datang');
    var kandang=$("[name=kandang]").val()
    $("[name=id_kartu]").val(id_datang)

    })


</script>
<script>
    $.ajaxSetup({
    headers: {
    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
    });
    $("#submit_datang_ovk").on("click", function () {
        var jumlah_awal=$("[name=jumlah_awal_ovk]").val();
        var jumlah=$("[name=jumlah_ovk]").val();
        var jumlah_keluar=$("[name=jumlah_ovk_keluar]").val();
        var jenis = $("[name=jenis_ovk_select]").val();
        var id_datang = $("[name=id_kartu]").val();
        var hari= $("[name=hari]").val();
        var kandang=$("[name=kandang]").val()
        console.log('jenis'+jenis);
        // console.log(jumlah +'jenis'+ jenis +'id'+ hari);
        if (!jenis) {
            $(".error_handling").html("<div class='alert alert-danger errored_info'>Harap isi jenis ovk</div>")
        }
        else{
            $('.errored_info').remove();
        }

        $.ajax({
        url:"{{ route('edit.record') }}",
        method:"POST",
        data:{
        key:'ovk',
        jenis:jenis,
        hari_datang:hari,
        kandang:kandang,
        jumlah_awal:jumlah_awal,
        jumlah:jumlah,
        jumlah_keluar:jumlah_keluar
        },
        success:function(data){
        $('#table-record').load(`{{ url('/jurnal-angkatan/table/${kandang}') }}`)
        $('#notif').html("<div class='alert alert-success' role='alert'> Berhasil diubah</div>")
        $("#exampleModalOvk .close").click()
        }
        })

    })
</script>

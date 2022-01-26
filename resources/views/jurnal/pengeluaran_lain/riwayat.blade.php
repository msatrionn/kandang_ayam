{{-- @php
dd($send)
@endphp --}}
<script src="{{ asset('assets/vendor/select2/select2.js') }}"></script>
<script>

</script>
<script>
    $(".submit_data").on('click',function () {
        var tgl=$(this).attr("data-tanggal");
        var ket=$(this).attr("data-keterangan");
        var kandang=$(this).attr("data-kandang");
        var pay=$(this).attr("data-payment");
        var id=$(this).attr("data-id");
        $("[name=tanggal]").val(tgl)
        $("[name=nominal_pengeluaran]").val(pay)
        $("[name=keterangan]").val(ket)
        $("[name=x_code]").val(id)

    })
</script>

@foreach ($data as $tgl)

<div class="card-body" style="padding: 0px;margin:10px 20px;">
    <div class="border rounded p-2">
        <span><strong class="text-primary">{{ $tgl->tanggal }}</strong></span>
        <input type="hidden" name="tgl" id="" value="{{ $tgl->tanggal }}">
        <?php
                                // $send = Header::getData($tgl->tanggal, $carian);
                                foreach ($send as $key => $row) {
                                    if ($tgl->tanggal==$row->tanggal) {
                                ?>
        <div class="row col-md-12">
            <div class="col-md-12">
                <div class="row mt-1">
                    <div class="col-md-10">
                        {{ $row->keterangan}}{{ $row->kandang_id ?
                        '(' . $row->kandang->nama . ')' : ''}}
                    </div>
                    <div class="text-right col-md-2">
                        <button class="btn btn-warning btn-sm text-right submit_data" data-toggle="modal"
                            data-target="#backdrop{{ $row->id }}" data-tanggal="{{ $row->tanggal }}"
                            data-keterangan="{{ $row->keterangan }}" data-kandang="{{ $row->kandang_id ?
                            '(' . $row->kandang->nama . ')' : ''}}" data-payment="{{ $row->payment }}"
                            data-id="{{ $row->id }}">
                            Aksi</button>
                    </div>
                </div>
                <span class="text-success">Rp. {{ number_format($row->total_trans) }}</span>

                <hr style="padding: 0;margin:0;">
            </div>
        </div>
        <div class="modal fade" id="backdrop{{ $row->id }}" data-backdrop="static" data-keyboard="false" tabindex="-1"
            aria-labelledby="backdrop{{ $row->id }}Label" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="backdrop{{ $row->id }}Label">Detail Pengeluaran</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="{{ route('keluarlain.update') }}" method="post">
                        @csrf @method('patch') <input type="hidden" name="x_code" value="{{ $row->id }}">
                        <div class="modal-body">
                            {{-- <div class="form-group">
                                Kandang
                                <select name="kandang" class="form-control select2" data-placeholder="Pilih Kandang"
                                    data-width="100%">
                                    <option value="ALL" {{ $row->kandang_id == NULL ? 'selected' : ''
                                        }}>Tanpa
                                        Kandang</option>
                                    @foreach ($kandang as $id => $list)
                                    <option value="{{ $id }}" {{ $row->kandang_id == $id ? 'selected' :
                                        ''
                                        }}>{{
                                        $list }}</option>
                                    @endforeach
                                </select>
                            </div> --}}

                            <div class="form-group">
                                Angkatan
                                <select name="angkatan" class="form-control select2" data-placeholder="Pilih Angkatan"
                                    data-width="100%">
                                    <option value=""></option>
                                    <option value="ALL">Tanpa Angkatan</option>
                                    @foreach ($angkatan as $id => $row)
                                    <option value="{{ $row->no }}">{{ $row->no }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                Tanggal
                                <input type="date" name="tanggal" class="form-control" value="{{ $row->tanggal }}"
                                    placeholder="Tuliskan Tanggal" autocomplete="off">
                            </div>

                            <div class="form-group">
                                Kas
                                <select name="select_kas" data-placeholder="Pilih Kas" class="form-control">
                                    <option value=""></option>
                                    @foreach ($payment as $id => $name)
                                    <option value="{{ $id }}" {{ $row->payment_method == $id ?
                                        'selected' :
                                        ''
                                        }}>{{ $name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                Nominal Pengeluaran
                                <input type="number" name="nominal_pengeluaran" class="form-control"
                                    value="{{ $row->payment }}" placeholder="Tuliskan Nominal Pengeluaran"
                                    autocomplete="off" data-politespace data-politespace-grouplength="3"
                                    data-politespace-delimiter="," data-politespace-decimal-mark="." step="0.01"
                                    data-politespace-reverse>
                                @error('nominal_pengeluaran') <div class="small text-danger">{{ $message
                                    }}
                                </div> @enderror
                            </div>

                            <div class="form-group">
                                Keterangan
                                <textarea name="keterangan" class="form-control" placeholder="Tuliskan Keterangan"
                                    rows="3">{{ $row->keterangan }}</textarea>
                                @error('keterangan') <div class="small text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Ubah</button>
                    </form>
                    <form action="{{ route('keluarlain.destroy') }}" method="post">
                        @csrf @method('delete') <input type="hidden" name="x_code" value="{{ $row->id }}">
                        <button type="submit" class="btn btn-danger">Hapus </button>
                    </form>
                </div>
            </div>
        </div>

    </div>
    <?php
                                    }
                                }
                                ?>
</div>
</div>
@endforeach

{{ $data->appends($_GET)->onEachSide(0)->links() }}

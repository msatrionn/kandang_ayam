<div class="row">
    <div class="col-md-6 pr-md-1">
        <div class="form-group">
            Kandang Saat Ini
            <input type="text" class="form-control" value="{{ $kandang->farm->nama }}" disabled>
        </div>

        <div class="form-group">
            Strain
            <input type="text" class="form-control" value="{{ $kandang->produk->nama }}" disabled>
            <input type="hidden" id="strain_select" name="strain_select" class="form-control"
                value="{{ $kandang->produk->strain }}" disabled>
        </div>
        {{-- <div class="form-group">
            Strain
            <div id="strain_selected">
                <select name="strain_select" data-placeholder="Pilih Strain" id="strain_select">
                    <option value=""></option>
                    @foreach ($strain as $row)
                    <option value="{{ $row->id }}" {{ ($kandang->strain_id == $row->id ? 'selected' : '') }}>{{
                        $row->nama }}</option>
                    @endforeach
                </select>
            </div>
            <input type="text" style="display: none" name="strain" id="strain" placeholder="Tulis Nama Strain"
                class="form-control">
            <label class="mt-2"><input id="input_strain" type="checkbox"> Input strain manual / Tidak ada di
                list</label>
        </div> --}}

        <div class="form-group">
            <button type="button" id="input_home" class="btn btn-block btn-primary">Submit</button>
        </div>
    </div>
    <div class="col-md-6 pl-md-1">
        <div class="row">
            <div class="col pr-1">
                <div class="form-group">
                    Populasi Awal
                    <input type="text" disabled value="{{ number_format($kandang->populasi) }} Ekor"
                        class="form-control">
                </div>
            </div>
            <div class="col pl-1">
                <div class="form-group">
                    Populasi Akhir
                    <input type="text" disabled value="{{ number_format($kandang->populasi_akhir) }} Ekor"
                        class="form-control">
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col pr-1">
                <div class="form-group">
                    Doc In
                    <input type="text" disabled value="{{ Tanggal::date($kandang->tanggal) }}" class="form-control">
                </div>
            </div>
            <div class="col pl-1">
                <div class="form-group">
                    Kematian
                    <input type="text" disabled
                        value="{{ round((($kandang->kematian / $kandang->populasi) * 100),2) }}%" class="form-control">
                </div>
            </div>
        </div>
    </div>
</div>


<script>
    // $(document).ready(function() {
    //     $(document).on('click', '#input_strain', function() {
    //         var input               =   $("#input_strain") ;
    //         var strain_selected     =   document.getElementById('strain_selected');
    //         var strain              =   document.getElementById('strain');

    //         if (input.prop('checked')){
    //             strain_selected.style   =   'display: none' ;
    //             strain.style            =   'display: block' ;
    //         } else {
    //             strain_selected.style   =   'display: block' ;
    //             strain.style            =   'display: none' ;
    //         }
    //     });
    // });

    $(document).ready(function() {
        $(document).on('click', '#check_kandang', function() {
            var input               =   $("#check_kandang") ;
            var kandang_selected    =   document.getElementById('kandang_selected');
            var kandang             =   document.getElementById('tulis_kandang');

            if (input.prop('checked')){
                kandang_selected.style   =   'display: none' ;
                kandang.style            =   'display: block' ;
            } else {
                kandang_selected.style   =   'display: block' ;
                kandang.style            =   'display: none' ;
            }
        });
    });
</script>

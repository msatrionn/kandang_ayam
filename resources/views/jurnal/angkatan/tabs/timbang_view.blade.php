<div class="border-top border-bottom py-2 mb-3">
    Hari Ke : {{ $hari }}<br>
    Tanggal : {{ Tanggal::date(Carbon\Carbon::parse($data->tanggal)->addDays(($hari - 1))) }}
</div>

<div class="table-responsive" style="height: 600px; overflow: auto;">
    <div class="wrapper">
        <table class="table table-sm table-bordered tabeles">
            <thead>
                <tr>
                    <th class="text-center border sticky-col sticky-head number-col" style="z-index: 98">NO</th>
                    <th class="text-center sticky-head">A</th>
                    <th class="text-center sticky-head">B</th>
                    <th class="text-center sticky-head">C</th>
                    <th class="text-center sticky-head">D</th>
                    <th class="text-center sticky-head">E</th>
                    <th class="text-center sticky-head">F</th>
                    <th class="text-center sticky-head">G</th>
                    <th class="text-center sticky-head">H</th>
                    <th class="text-center sticky-head">I</th>
                    <th class="text-center sticky-head">J</th>
                    <th class="text-center sticky-head">K</th>
                    <th class="text-center sticky-head">L</th>
                </tr>
            </thead>
            <tbody>
                @php
                if ($timbang) {
                    $row    =   json_decode($timbang->data_timbang);
                    $count  =   count($row);
                } else {
                    $count  =   600 ;
                }
                @endphp
                @for ($i = 0; $i < 50; $i++)
                    <tr>
                        <td class="text-center sticky-col number-col">{{ $i + 1 }}</td>
                        @for ($x = 0; $x <= $count; $x++)
                            @if ($x >= ($i * 12) && $x < (($i + 1) * 12))
                            <td class="text-center p-0 m-0">
                                <input type="number" style="min-width: 50px" value="{{ $row[$x] ?? '' }}" onkeyup="hitung_berat()" class="timbang_berat m-0 p-0 form-control form-control-sm border-0 rounded-0 text-center" step="0.01">
                            </td>
                            @endif
                        @endfor
                    </tr>
                @endfor
            </tbody>
        </table>
    </div>
</div>

<div class="row">
    <div class="col-md-4 col-6">
        <div class="form-group">
            <div class="small text-center bg-light p-1">Total Berat</div>
            <input type="text" disabled id="jumlah_timbang" value="{{ $timbang->berat ?? 0 }}" class="form-control text-center rounded-0">
        </div>
    </div>
    <div class="col-md-4 col-6">
        <div class="form-group">
            <div class="small text-center bg-light p-1">Jumlah Ekor</div>
            <input type="text" disabled id="jumlah_ekor" value="{{ $timbang->jumlah ?? 0 }}" class="form-control text-center rounded-0">
        </div>
    </div>
    <div class="col-md-4 col-12">
        <div class="form-group">
            <div class="small text-center bg-light p-1">Rata-Rata</div>
            <input type="text" disabled id="rata_ekor" value="{{ $timbang->ratarata ?? 0 }}" class="form-control text-center rounded-0">
        </div>
    </div>
</div>

<div class="form-group">
    <button type="button" id="input_timbang" class="btn btn-block btn-primary">Submit</button>
</div>

<script>
    function hitung_berat() {
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

        document.getElementById('jumlah_timbang').value =   total ;
        document.getElementById('jumlah_ekor').value    =   ekor ;
        document.getElementById('rata_ekor').value      =   (total/ekor).toFixed(2) ;
    }
</script>

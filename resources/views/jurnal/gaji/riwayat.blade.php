@foreach ($gaji as $row)
<div class="border rounded cursor data-search mb-2 py-1 px-2" data-toggle="modal" data-target="#modal{{ $row->id }}">
    <div class="row">
        <div class="col pr-1">{{ $row->karyawan->name }}</div>
        <div class="col-auto pl-1 text-right">{{ Tanggal::date($row->tanggal) }}</div>
    </div>

    <div class="row">
        <div class="col pr-1">Gaji {{ $row->metode_gaji }} {{ $row->metode_gaji == 'harian' ? '(' . $row->hari_gaji . '
            hari)' : '' }}</div>
        <div class="col-auto pl-1 text-right">Rp {{ number_format($row->total_didapat) }}</div>
    </div>
</div>

<div class="modal fade" id="modal{{ $row->id }}" data-backdrop="static" data-keyboard="false" tabindex="-1"
    aria-labelledby="modal{{ $row->id }}Label" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal{{ $row->id }}Label">Detail Gaji</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-sm">
                    <tbody>
                        <tr>
                            <td>Tanggal Penggajian</td>
                            <td>{{ Tanggal::date($row->tanggal) }}</td>
                        </tr>
                        <tr>
                            <td>Nama Karyawan</td>
                            <td>{{ $row->karyawan->name }}</td>
                        </tr>
                        <tr>
                            <td>Gaji Pokok</td>
                            <td>Rp {{ number_format($row->besar_gaji * $row->hari_gaji) }}</td>
                        </tr>
                        <tr>
                            <td>Over Time</td>
                            <td>Rp {{ number_format($row->besar_overtime * $row->perkalian_overtime) }}</td>
                        </tr>
                        <tr>
                            <td>Potongan Gaji</td>
                            <td>Rp {{ number_format($row->potong_gaji) }}</td>
                        </tr>
                        <tr>
                            <td>THR</td>
                            <td>Rp {{ number_format($row->thr) }}</td>
                        </tr>
                        <tr>
                            <td>Bonus</td>
                            <td>Rp {{ number_format($row->bonus) }}</td>
                        </tr>
                        <tr>
                            <td>Keterangan Bonus</td>
                            <td>{{ $row->keterangan }}</td>
                        </tr>
                        <tr>
                            <td>Cicilan Cashbon</td>
                            <td>Rp {{ number_format($row->cashbon) }}</td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold">Gaji Diperoleh</td>
                            <td class="font-weight-bold">Rp {{ number_format($row->total_didapat) }}</td>
                        </tr>
                        <tr>
                            <td>Pembayaran Gaji</td>
                            <td>{{ $row->pay->nama }}</td>
                        </tr>
                    </tbody>
                </table>
                <form action="{{ route('gaji.destroy') }}" method="post">
                    @csrf @method('delete') <input type="hidden" name="x_code" value="{{ $row->id }}">
                    <button type="submit" class="btn btn-sm btn-danger float-right">Hapus</button>
                </form>
                <a href="{{ route('gaji.index', ['key' => 'pdf', 'id' => $row->id]) }}"
                    class="btn btn-outline-danger btn-sm"><i class="fa fa-file-pdf-o"></i> Unduh PDF</a>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endforeach

<div id="daftar_paginate">
    {{ $gaji->appends($_GET)->onEachSide(0)->links() }}
</div>


<script>
    $(".pagination").attr('class', 'pagination pagination-sm pt-2');
$('#daftar_paginate .pagination a').on('click', function(e) {
    e.preventDefault();

    url = $(this).attr('href') ;
    $.ajax({
        url: url,
        method: "GET",
        success: function(response) {
            $('#riwayat_gaji').html(response);
        }

    });
});
</script>

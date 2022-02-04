<table class="table table-sm">

    <tr>
        <th>PENDAPATAN OPERASIONAL</th>
        <th></th>
    </tr>
    <tr>
        <th>&nbsp; &nbsp; &nbsp;Penjualan ayam</th>
        <th></th>
    </tr>
    <tr>
        <td>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;Penjualan Ayam</td>
        <td class="text-right">Rp .{{ number_format($result['penjualan_ayam'], 2) }}</td>
    </tr>
    <tr>
        <th>&nbsp; &nbsp; &nbsp;Penjualan Lain</th>
        <th></th>
    </tr>
    @php
    $total_lain=0
    @endphp
    @foreach ($result['penjualan_lain'] as $item)
    <tr>
        <td>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;{{ $item->keterangan }}</td>
        <td class="text-right">
            Rp. {{ number_format($item->total_trans, 2) }}</td>
    </tr>
    @php
    $total_lain += $item->total_trans
    @endphp
    @endforeach
    <tr>
        <th>&nbsp; &nbsp; &nbsp;Total penjualan lain</th>
        <th class="text-right">Rp. {{ number_format($total_lain, 2) }}</th>
    </tr>
    <tr>
        <th>TOTAL PENDAPATAN OPERASIONAL</th>
        <th class="text-right">
            Rp. {{ number_format($result['penjualan_ayam'] + $total_lain, 2) }}
        </th>
    </tr>
    <tr></tr>
    <tr>
        <th>PENGELUARAN</th>
        <th></th>
    </tr>
    <tr>
        <th>&nbsp; &nbsp; &nbsp;pengeluaran</th>
        <th></th>
    </tr>
    @php
    $tot_pengeluaran=0;
    @endphp
    @foreach ($result['pengeluaran_lain'] as $item)
    <tr>
        <td>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;{{ $item->keterangan }}</td>
        <td class="text-right">Rp. {{ number_format($item->total_trans, 2) }}</td>
    </tr>
    @php
    $tot_pengeluaran += $item->total_trans
    @endphp
    @endforeach
    <tr>
        <th>&nbsp; &nbsp; &nbsp;Total pengeluaran</th>
        <th class="text-right">Rp. {{ number_format($tot_pengeluaran, 2) }}</th>
    </tr>
    <tr>
        <td>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;Penggajian Karyawan</td>
        <td class="text-right">Rp. {{ number_format($result['penggajian_karyawan'], 2) }}</td>
    </tr>
    <th>TOTAL PENGELUARAN</th>
    <th class="text-right">Rp. {{ number_format($tot_pengeluaran + $result['penggajian_karyawan'], 2) }}
    </th>
    </tr>
    <tr></tr>
    <tr>
        <th>TOTAL LABA</th>
        {{-- <th class="text-right">{{ number_format(($result['penjualan_ayam'] +
            $result['penjualan_lain'])-($result['pengeluaran_lain'] + $result['penggajian_karyawan']), 2)
            }}
        </th> --}}
    </tr>
    {{-- <tr>
        <th>HPP</th>
        <th></th>
    </tr>
    <tr>
        <td>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;Harga Pokok Penjualan</td>
        <td></td>
    </tr>
    <tr>
        <th>TOTAL HPP</th>
        <td class="text-right">{{ number_format($result['hpp'], 2) }}</td>
    </tr>
    <tr class="bg-light">
        <th>LABA KOTOR</th>
        <th></th>
    </tr> --}}
</table>

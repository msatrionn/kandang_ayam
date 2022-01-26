@php
    header('Content-Transfer-Encoding: none');
    header("Content-type: application/vnd-ms-excel");
    header("Content-type: application/x-msexcel");
    header("Content-Disposition: attachment; filename=Laporan Delivery Order.xls");
@endphp
<table>
    <tbody>
        <tr>
            <th colspan="10">Laporan Delivery Order</th>
        </tr>
        <tr>
            <th colspan="10">Periode {{ Tanggal::date($request->mulai) . ' - ' . Tanggal::date($request->selesai) }}</th>
        </tr>
        <tr>
            <th colspan="10"></th>
        </tr>
    </tbody>
</table>

<table border="1">
    <thead>
        <tr>
            <th>No</th>
            <th>Tanggal Kirim</th>
            <th>Nomor PO</th>
            <th>Jenis</th>
            <th>Produk</th>
            <th>Jumlah Pengiriman</th>
            <th>Biaya Kirim</th>
            <th>Beban Angkut</th>
            <th>Biaya Lain-Lain</th>
            <th>Metode Pembayaran</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $i => $row)
        <tr>
            <td>{{ ++$i }}</td>
            <td>{{ $row->tanggal }}</td>
            <td>{{ $row->purchasing->nomor_purchasing }}</td>
            <td>{{ $row->produk->tipeset->nama }}</td>
            <td>{{ $row->produk->nama }}</td>
            <td>{{ $row->qty }}</td>
            <td>{{ $row->biaya_pengiriman }}</td>
            <td>{{ $row->beban_angkut }}</td>
            <td>{{ $row->biaya_lain }}</td>
            <td>{{ $row->kas ? $row->metode->nama : '' }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

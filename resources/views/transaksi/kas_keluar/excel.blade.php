@php
    header('Content-Transfer-Encoding: none');
    header("Content-type: application/vnd-ms-excel");
    header("Content-type: application/x-msexcel");
    header("Content-Disposition: attachment; filename=Pembayaran Purchase Order.xls");
@endphp
<table>
    <tbody>
        <tr>
            <th colspan="6">Pembayaran Purchase Order</th>
        </tr>
        <tr>
            <th colspan="6">Periode {{ Tanggal::date($request->mulai) . ' - ' . Tanggal::date($request->selesai) }}</th>
        </tr>
        <tr>
            <th colspan="6"></th>
        </tr>
    </tbody>
</table>

<table border="1">
    <thead>
        <tr>
            <th>No</th>
            <th>Tanggal</th>
            <th>Nomor PO</th>
            <th>Supplier</th>
            <th>Nominal Pembayaran</th>
            <th>Metode Pembayaran</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($trans as $i => $row)
        <tr>
            <td>{{ ++$i }}</td>
            <td>{{ $row->tanggal }}</td>
            <td>{{ $row->purchase->nomor_purchasing }}</td>
            <td>{{ $row->purchase->supplier->nama }}</td>
            <td>{{ $row->nominal }}</td>
            <td>{{ $row->method->nama ?? '' }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

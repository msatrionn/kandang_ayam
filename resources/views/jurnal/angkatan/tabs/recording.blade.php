<a href="{{ route('angkatanayam.index', ['key' => 'show_data', 'act' => 'unduh_recording', 'kandang' => $kandang->id]) }}"
    class="btn btn-success float-right">Unduh </a>

<div class="form-group">
    @php
    $exp = json_decode($kandang->farm->json_data);
    @endphp
    Kandang : {{ $kandang->farm->nama }}<br>
    Doc In : {{ Tanggal::date($kandang->tanggal) }}
</div>

<div class="table-responsive" style="height: 600px; overflow: auto;">
    <div class="wrapper">
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
                    <th class="text-center sticky-heading sticky-head">Mati</th>
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
                            Tanggal::date(Carbon\Carbon::parse($kandang->tanggal)->addDays(($i - 1))) }}</td>
                        <td class="text-center sticky-col hari-col" style="z-index: 97">{{ $i }}</td>
                        @if ($i%7 == 1)
                        <td class="text-center sticky-col minggu-col" style="z-index: 97" rowspan="7">{{ ($i%7 == 1) ?
                            round($i / 7) + 1 : '' }}</td>
                        @endif
                        <td class="text-center">{{ Populasi::view_record($kandang->id, $i) ? $mati : '' }}</td>
                        <td class="text-center">{{ Populasi::view_record($kandang->id, $i) ? $afkir : '' }}</td>
                        <td class="text-center">{{ Populasi::view_record($kandang->id, $i) ? $panen : '' }}</td>
                        <td class="text-center table-secondary">{{ Populasi::view_record($kandang->id, $i) ?
                            ($kandang->populasi - $hidup) : '' }}</td>
                        <td class="text-center">{{ (Kartu::view_pakan($kandang->id, $i, 'masuk') > 0) ?
                            Kartu::view_pakan($kandang->id, $i, 'masuk') : '' }}</td>
                        <td class="text-center">{{ (Kartu::view_pakan($kandang->id, $i, 'keluar') > 0) ?
                            Kartu::view_pakan($kandang->id, $i, 'keluar') : '' }}</td>
                        <td class="text-center">{{ (Kartu::view_pakan($kandang->id, $i, 'masuk') +
                            Kartu::view_pakan($kandang->id, $i, 'keluar')) ? ($masuk - $keluar) : '' }}</td>
                        <td class="text-center table-secondary">{{ ($gram_ekor > 0) ? $gram_ekor : '' }}</td>
                        <td class="text-center">{{ Strain::strain_angka('gram', $kandang->strain_id, $i) }}</td>
                        <td class="text-center table-secondary">{{ ($gram_ekor > 0) ? $kumulatif : '' }}</td>
                        <td class="text-center">{{ Strain::strain_angka('global', $kandang->strain_id, $i) }}</td>
                        <td class="text-center">{{ substr(Kartu::view_pakan($kandang->id, $i, 'pakan'), 0, -2) }}</td>
                        <td class="text-center">{{ (Timbang::hasil_timbang($kandang->id, $i) > 0) ?
                            Timbang::hasil_timbang($kandang->id, $i) : '' }}</td>
                        <td class="text-center">{{ Strain::strain_angka('bb', $kandang->strain_id, $i) }}</td>
                        <td class="text-center">{{ substr(Kartu::view_ovk($kandang->id, $i), 0, -2) }}</td>
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
                    @endfor
            </tbody>
        </table>
    </div>
</div>

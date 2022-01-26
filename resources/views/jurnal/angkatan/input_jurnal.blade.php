<ul class="nav nav-tabs mt-4" id="myTab" role="tablist">
    <li class="nav-item" role="presentation">
        <a class="nav-link {{ $tab ? ($tab == 'home' ? 'active' : '') : 'active' }}" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Home</a>
    </li>
    <li class="nav-item" role="presentation">
        <a class="nav-link {{ $tab == 'pakan' ? 'active' : '' }}" id="pakan-tab" data-toggle="tab" href="#pakan" role="tab" aria-controls="pakan" aria-selected="false">Pakan</a>
    </li>
    <li class="nav-item" role="presentation">
        <a class="nav-link {{ $tab == 'ovk' ? 'active' : '' }}" id="ovk-tab" data-toggle="tab" href="#ovk" role="tab" aria-controls="ovk" aria-selected="false">OVK</a>
    </li>
    <li class="nav-item" role="presentation">
        <a class="nav-link {{ $tab == 'populasi' ? 'active' : '' }}" id="populasi-tab" data-toggle="tab" href="#populasi" role="tab" aria-controls="populasi" aria-selected="false">Populasi</a>
    </li>
    <li class="nav-item" role="presentation">
        <a class="nav-link {{ $tab == 'vaksinasi' ? 'active' : '' }}" id="vaksinasi-tab" data-toggle="tab" href="#vaksinasi" role="tab" aria-controls="vaksinasi" aria-selected="false">Vaksinasi</a>
    </li>
    <li class="nav-item" role="presentation">
        <a class="nav-link {{ $tab == 'timbang' ? 'active' : '' }}" id="timbang-tab" data-toggle="tab" href="#timbang" role="tab" aria-controls="timbang" aria-selected="false">Timbang</a>
    </li>
    <li class="nav-item" role="presentation">
        <a class="nav-link {{ $tab == 'catatan' ? 'active' : '' }}" id="catatan-tab" data-toggle="tab" href="#catatan" role="tab" aria-controls="catatan" aria-selected="false">Keterangan</a>
    </li>
    <li class="nav-item" role="presentation">
        <a class="nav-link {{ $tab == 'kasus' ? 'active' : '' }}" id="kasus-tab" data-toggle="tab" href="#kasus" role="tab" aria-controls="kasus" aria-selected="false">Kasus</a>
    </li>
    <li class="nav-item" role="presentation">
        <a class="nav-link {{ $tab == 'import' ? 'active' : '' }}" id="import-tab" data-toggle="tab" href="#import" role="tab" aria-controls="import" aria-selected="false">Import</a>
    </li>
    <li class="nav-item" role="presentation">
        <a class="nav-link {{ $tab == 'recording' ? 'active' : '' }}" id="recording-tab" data-toggle="tab" href="#recording" role="tab" aria-controls="recording" aria-selected="false">Recording</a>
    </li>
    <li class="nav-item" role="presentation">
        <a class="nav-link {{ $tab == 'mutasi' ? 'active' : '' }}" id="mutasi-tab" data-toggle="tab" href="#mutasi" role="tab" aria-controls="mutasi" aria-selected="false">Mutasi</a>
    </li>
</ul>

<div class="tab-content border border-top-0" id="myTabContent">
    <div class="tab-pane fade {{ $tab ? ($tab == 'home' ? 'show active' : '') : 'show active' }}" id="home" role="tabpanel" aria-labelledby="home-tab">
        @include('jurnal.angkatan.tabs.home')
    </div>
    <div class="tab-pane fade {{ $tab == 'pakan' ? 'show active' : '' }}" id="pakan" role="tabpanel" aria-labelledby="pakan-tab">
        @include('jurnal.angkatan.tabs.pakan')
    </div>
    <div class="tab-pane fade {{ $tab == 'ovk' ? 'show active' : '' }}" id="ovk" role="tabpanel" aria-labelledby="ovk-tab">
        @include('jurnal.angkatan.tabs.ovk')
    </div>
    <div class="tab-pane fade {{ $tab == 'populasi' ? 'show active' : '' }}" id="populasi" role="tabpanel" aria-labelledby="populasi-tab">
        @include('jurnal.angkatan.tabs.populasi')
    </div>
    <div class="tab-pane fade {{ $tab == 'vaksinasi' ? 'show active' : '' }}" id="vaksinasi" role="tabpanel" aria-labelledby="vaksinasi-tab">
        @include('jurnal.angkatan.tabs.vaksinasi')
    </div>
    <div class="tab-pane fade {{ $tab == 'timbang' ? 'show active' : '' }}" id="timbang" role="tabpanel" aria-labelledby="timbang-tab">
        @include('jurnal.angkatan.tabs.timbang')
    </div>
    <div class="tab-pane fade {{ $tab == 'catatan' ? 'show active' : '' }}" id="catatan" role="tabpanel" aria-labelledby="catatan-tab">
        @include('jurnal.angkatan.tabs.keterangan')
    </div>
    <div class="tab-pane fade {{ $tab == 'kasus' ? 'show active' : '' }}" id="kasus" role="tabpanel" aria-labelledby="kasus-tab">
        @include('jurnal.angkatan.tabs.kasus')
    </div>
    <div class="tab-pane fade {{ $tab == 'import' ? 'show active' : '' }}" id="import" role="tabpanel" aria-labelledby="import-tab">
        @include('jurnal.angkatan.tabs.recording_import')
    </div>
    <div class="tab-pane fade {{ $tab == 'recording' ? 'show active' : '' }}" id="recording" role="tabpanel" aria-labelledby="recording-tab">
        @include('jurnal.angkatan.tabs.recording')
    </div>
    <div class="tab-pane fade {{ $tab == 'mutasi' ? 'show active' : '' }}" id="mutasi" role="tabpanel" aria-labelledby="mutasi-tab">
        @include('jurnal.angkatan.tabs.mutasi')
    </div>
</div>

<script>
    $(function () {
        $('select').each(function () {
            $(this).select2({
            theme: 'bootstrap4',
            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
            placeholder: $(this).data('placeholder'),
            allowClear: Boolean($(this).data('allow-clear')),
            closeOnSelect: !$(this).attr('multiple'),
            });
        });
    });
</script>

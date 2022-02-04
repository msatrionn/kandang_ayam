@extends('layouts.main')

@section('title', 'Report Pengeluaran')
@section('footer')
<script>
    $('.doc').load("{{ route('reportpengeluaran.index',['key'=>'doc']) }}")
    $('.pakan').load("{{ route('reportpengeluaran.index',['key'=>'pakan']) }}")
    $('.ovk').load("{{ route('reportpengeluaran.index',['key'=>'ovk']) }}")
    $('.pemanas').load("{{ route('reportpengeluaran.index',['key'=>'pemanas']) }}")
    $('.tk').load("{{ route('reportpengeluaran.index',['key'=>'tk']) }}")
    $('.listrik').load("{{ route('reportpengeluaran.index',['key'=>'listrik']) }}")
    $('.penyusutan').load("{{ route('reportpengeluaran.index',['key'=>'penyusutan']) }}")
    $('.transport').load("{{ route('reportpengeluaran.index',['key'=>'transport']) }}")
    $('.humas').load("{{ route('reportpengeluaran.index',['key'=>'humas']) }}")
    $('.operasional').load("{{ route('reportpengeluaran.index',['key'=>'operasional']) }}")
</script>
@endsection
@section('content')
<div class="card">
    <div class="card-body">
        <div class="accordion" id="accordionData">
            <div class="card">
                <div class="card-header" id="heading1">
                    <p class="mb-0">
                        <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse"
                            data-target="#collapse1" aria-expanded="true" aria-controls="collapse1">
                            DOC
                        </button>
                    </p>
                </div>
                <div id="collapse1" class="collapse show" aria-labelledby="heading1" data-parent="#accordionData">
                    <div class="doc"></div>
                </div>
            </div>
        </div>
        <div class="accordion" id="accordionData">
            <div class="card">
                <div class="card-header" id="heading2">
                    <p class="mb-0">
                        <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse"
                            data-target="#collapse2" aria-expanded="true" aria-controls="collapse2">
                            PAKAN
                        </button>
                    </p>
                </div>
                <div id="collapse2" class="collapse show" aria-labelledby="heading2" data-parent="#accordionData">
                    <div class="pakan"></div>
                </div>
            </div>
        </div>
        <div class="accordion" id="accordionData">
            <div class="card">
                <div class="card-header" id="heading3">
                    <p class="mb-0">
                        <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse"
                            data-target="#collapse3" aria-expanded="true" aria-controls="collapse3">
                            OVK
                        </button>
                    </p>
                </div>
                <div id="collapse3" class="collapse show" aria-labelledby="heading3" data-parent="#accordionData">
                    <div class="ovk"></div>
                </div>
            </div>
        </div>
        <div class="accordion" id="accordionData">
            <div class="card">
                <div class="card-header" id="heading4">
                    <p class="mb-0">
                        <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse"
                            data-target="#collapse4" aria-expanded="true" aria-controls="collapse4">
                            PENGELUARAN PEMANAS
                        </button>
                    </p>
                </div>
                <div id="collapse4" class="collapse show" aria-labelledby="heading4" data-parent="#accordionData">
                    <div class="pemanas"></div>
                </div>
            </div>
        </div>
        <div class="accordion" id="accordionData">
            <div class="card">
                <div class="card-header" id="heading5">
                    <p class="mb-0">
                        <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse"
                            data-target="#collapse5" aria-expanded="true" aria-controls="collapse5">
                            PENGELUARAN TENAGA KERJA
                        </button>
                    </p>
                </div>
                <div id="collapse5" class="collapse show" aria-labelledby="heading5" data-parent="#accordionData">
                    <div class="tk"></div>
                </div>
            </div>
        </div>
        <div class="accordion" id="accordionData">
            <div class="card">
                <div class="card-header" id="heading6">
                    <p class="mb-0">
                        <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse"
                            data-target="#collapse6" aria-expanded="true" aria-controls="collapse6">
                            TOKEN LISTRIK
                        </button>
                    </p>
                </div>
                <div id="collapse6" class="collapse show" aria-labelledby="heading6" data-parent="#accordionData">
                    <div class="listrik"></div>
                </div>
            </div>
        </div>
        <div class="accordion" id="accordionData">
            <div class="card">
                <div class="card-header" id="heading7">
                    <p class="mb-0">
                        <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse"
                            data-target="#collapse7" aria-expanded="true" aria-controls="collapse7">
                            SEWA KANDANG/PENYUSUTAN
                        </button>
                    </p>
                </div>
                <div id="collapse7" class="collapse show" aria-labelledby="heading7" data-parent="#accordionData">
                    <div class="penyusutan"></div>
                </div>
            </div>
        </div>
        <div class="accordion" id="accordionData">
            <div class="card">
                <div class="card-header" id="heading8">
                    <p class="mb-0">
                        <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse"
                            data-target="#collapse8" aria-expanded="true" aria-controls="collapse8">
                            TRANSPORT
                        </button>
                    </p>
                </div>
                <div id="collapse8" class="collapse show" aria-labelledby="heading8" data-parent="#accordionData">
                    <div class="transport"></div>
                </div>
            </div>
        </div>
        <div class="accordion" id="accordionData">
            <div class="card">
                <div class="card-header" id="heading9">
                    <p class="mb-0">
                        <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse"
                            data-target="#collapse9" aria-expanded="true" aria-controls="collapse9">
                            HUMAS
                        </button>
                    </p>
                </div>
                <div id="collapse9" class="collapse show" aria-labelledby="heading9" data-parent="#accordionData">
                    <div class="humas"></div>
                </div>
            </div>
        </div>
        <div class="accordion" id="accordionData">
            <div class="card">
                <div class="card-header" id="heading10">
                    <p class="mb-0">
                        <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse"
                            data-target="#collapse10" aria-expanded="true" aria-controls="collapse10">
                            OPERASIONAL
                        </button>
                    </p>
                </div>
                <div id="collapse10" class="collapse show" aria-labelledby="heading10" data-parent="#accordionData">
                    <div class="operasional"></div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection

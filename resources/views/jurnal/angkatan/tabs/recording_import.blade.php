<form id="data" method="post" enctype="multipart/form-data">
    @csrf
    <div class="input-group mb-3">
        <input type="file" name="file" id="file" class="form-control" placeholder="Recipient's username"
            aria-label="Recipient's username" aria-describedby="button-addon2" required>
        <input type="hidden" name="tgl" id="" value="{{ $kandang->tanggal }}">
        <button class="btn btn-primary submit_import" type="submit" id="button-addon2">Import</button>
    </div>
</form>
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
        <input type="hidden" name="kandang" id="" value="{{ $kandang->id }}">
        <div id="table-record"></div>
    </div>
</div>

<script>
    var kandangs=$("[name=kandang]").val()
    $('#table-record').load(`{{ url('/jurnal-angkatan/table/${kandangs}') }}`)
</script>
<script>
  $("form#data").submit(function(e) {
    e.preventDefault();
    var formData = new FormData(this);

    $.ajax({
        url: "{{ route('import.record') }}",
        type: 'POST',
        data: formData,
        success: function (data) {
            $('#table-record').load(`{{ url('/jurnal-angkatan/table/${kandangs}') }}`)
        $('#notif').html("<div class='alert alert-success' role='alert'> Berhasil Import</div>")

        },
        cache: false,
        contentType: false,
        processData: false
    });
});
</script>

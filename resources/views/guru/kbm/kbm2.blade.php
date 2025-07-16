@extends('layout.main')
@section('content')
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><i class="nav-icon fas fa-chalkboard-teacher"></i> Data KBM</h3>
            </div>
            <div class="card-body">
                <h5>Tahun Akademik : {{ $taAktif->tahun }} {{ $taAktif->semester == 1 ? 'Ganjil' : 'Genap' }}</h5>
                <br>
               
                <table id="example1" class="table table-bordered table-striped">
                    <thead>
                    <tr>
                      <th>No</th>
                      <th>Nama KBM</th>
                      <th>Guru</th>
                      <th>Waktu</th>
                      <th><center>Aksi</center></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($kbm as $item)
                    <tr>
                        <td>{{ $loop->iteration; }}</td>
                        <td>
                            {{ $item->mapel->nama_mapel }} <br>
                            {{-- {{ $item->kelas->nama_kelas }} --}}
                        </td>
                        <td>
                            {{ $item->guru->nip }} <br>
                            {{ $item->guru->nama }}
                        </td>
                        <td>
                            {{ hariNum($item->hari) }} <br>
                            {{ $item->jam_mulai }} - {{ $item->jam_selesai }} <br>
                        </td>
                        <td>
                          <center>
                            <a href="/guru/kbm/detail/presensi/{{ $item->id }}" class="btn btn-info btn-sm">
                                <i class="nav-icon fas fa-eye"></i> Presensi</a>
                            </button>    
                          </center>
                        </td>
                    </tr>
                    @endforeach
                    </tbody>
                  </table>
            </div>
        </div>
    </div>
   
    <!-- Modal Dinamis -->
    <div class="modal fade" id="modalShow" tabindex="-1" role="dialog" aria-labelledby="modalShowLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="modalShowLabel">Presensi</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div id="mdLd" class="p-4 text-center">
                    <div class="spinner-border text-primary" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                    <div>Loading content...</div>
                </div>
                <div class="modal-body" id="mdBd" style="display: none;"></div>
            </div>
        </div>
    </div>

    <script>
    $(document).on("click", ".open-modal", function () {
        $("#mdBd").hide();
        $("#mdLd").show();
        $("#modalShow").modal({
            backdrop: "static",
            keyboard: false,
        });
        $("#modalShow").modal("show");

        $("#mdBd").load($(this).data("url"), function (response, status, xhr) {
            if (status === "success") {
                setTimeout(function () {
                    $("#mdBd").show();
                    $("#mdLd").hide();
                }, 200);
            } else {
                console.error("Gagal load konten modal:", xhr.statusText);
                $("#mdLd").html('<div class="text-danger">Gagal memuat data.</div>');
            }
        });
    });
    </script>
@endsection
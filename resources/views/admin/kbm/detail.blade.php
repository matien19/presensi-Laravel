@extends('layout.main')
@section('content')
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><i class="nav-icon fas fa-chalkboard-teacher"></i> Data KBM</h3>
            </div>
            <div class="card-body">
                <div class="text-center mb-4">
                    <h5><b>Kelas {{ $kelas->nama_kelas }} </b></h5><h6>( {{ $kelas->kelasSiswa->count() }} Siswa )</h6>
                </div>
                <h5>Tahun Akademik : {{ $kelas->tahunAjaran->tahun }} {{ $kelas->tahunAjaran->semester == 1 ? 'Ganjil' : 'Genap' }}</h5>
                <br>
                <div class="row">
                    <div class="col-12">
                        <h4>Jadwal KBM</h4>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="card card-primary card-tabs">
                            <div class="card-header p-0 pt-1">
                                <ul class="nav nav-tabs" id="kbm-tabs" role="tablist">
                                    @php
                                        $days = [
                                            1 => 'Senin',
                                            2 => 'Selasa',
                                            3 => 'Rabu',
                                            4 => 'Kamis',
                                            5 => 'Jumat',
                                            6 => 'Sabtu'
                                        ];
                                    @endphp
                                    @foreach($days as $num => $day)
                                        <li class="nav-item">
                                            <a class="nav-link {{ $num == 1 ? 'active' : '' }}" id="tab-{{ $num }}" data-toggle="pill" href="#kbm-{{ $num }}" role="tab" aria-controls="kbm-{{ $num }}" aria-selected="{{ $num == 1 ? 'true' : 'false' }}">{{ $day }}</a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                            <div class="card-body">
                                <div class="tab-content" id="kbm-tabs-content">
                                    @foreach($days as $num => $day)
                                        <div class="tab-pane fade {{ $num == 1 ? 'show active' : '' }}" id="kbm-{{ $num }}" role="tabpanel" aria-labelledby="tab-{{ $num }}">
                                            @php
                                                $kbmHari = $kbm[$num] ?? collect();
                                            @endphp
                                            @if($kbmHari->count())
                                                <div class="d-flex justify-content-between mb-3">
                                                    <h5 class="mb-0">Jadwal KBM Hari {{ $day }}</h5>
                                                    <button type="button" class="btn btn-primary btn-xs" data-toggle="modal" data-target="#modal-default" data-hari="{{ $num }}">
                                                        <i class="nav-icon fas fa-plus"></i> Tambah KBM
                                                    </button>
                                                </div>
                                                <ul class="list-group list-group-flush">
                                                    @foreach($kbmHari as $item)
                                                        <div class="callout callout-success border border-success mb-2">
                                                            <li class="list-group-item d-flex align-items-center justify-content-between p-2">
                                                                <div class="d-flex align-items-center">
                                                                    <div class="me-3 text-center" style="min-width:70px;">
                                                                        <span class="fw-bold">
                                                                            @if(isset($item['jam_mulai']))
                                                                                {{ \Carbon\Carbon::parse($item['jam_mulai'])->format('H:i') }}
                                                                            @else
                                                                                -
                                                                            @endif
                                                                        </span>
                                                                        <br>
                                                                        <small class="text-muted">
                                                                            @if(isset($item['jam_selesai']))
                                                                                {{ \Carbon\Carbon::parse($item['jam_selesai'])->format('H:i') }}
                                                                            @else
                                                                                -
                                                                            @endif
                                                                        </small>
                                                                    </div>
                                                                    <div>
                                                                        <div class="fw-bold">{{ $item['mapel']['nama_mapel'] ?? '-' }}</div>
                                                                        <div class="text-muted small">{{ $kelas->nama_kelas }} - {{ $item['guru']['nama'] ?? '-' }}</div>
                                                                    </div>
                                                                </div>
                                                                <div class="d-flex flex-wrap align-items-center gap-1 btn-group-sm" role="group" style="gap: 0.5rem;">
                                                                    <a href="/kbm/detail/presensi/{{ $id }}/{{ $item['id'] }}"  class="btn btn-primary btn-xs btn-presensi open-modal" >
                                                                        <i class="nav-icon fas fa-qrcode"></i> Presensi
                                                                    </a>
                                                                    <button type="button" class="btn btn-primary btn-xs" data-toggle="modal" data-target="#modal-default"
                                                                        data-id="{{ $item['id'] }}"
                                                                        data-guru="{{ $item['guru']['nip'] }}"
                                                                        data-mapel="{{ $item['mapel']['id'] }}"
                                                                        data-kelas="{{ $item['kelas']['id'] }}"
                                                                        data-hari="{{ $item['hari'] }}"
                                                                        data-jam_mulai="{{ $item['jam_mulai'] }}"
                                                                        data-jam_selesai="{{ $item['jam_selesai'] }}">
                                                                        <i class="nav-icon fas fa-edit"></i> Edit
                                                                    </button>
                                                                    <button type="button" class="btn btn-danger btn-xs btn-hapus" data-id="{{ $item['id'] }}">
                                                                        <i class="nav-icon fas fa-trash"></i> Hapus
                                                                    </button>
                                                                </div>
                                                            </li>
                                                        </div>
                                                    @endforeach
                                                </ul>
                                            @else
                                                <div class="d-flex justify-content-between mb-3">
                                                    <h5 class="mb-0">Jadwal KBM Hari {{ $day }}</h5>
                                                    <button type="button" class="btn btn-primary btn-xs" data-toggle="modal" data-target="#modal-default" data-hari="{{ $num }}">
                                                        <i class="nav-icon fas fa-plus"></i> Tambah KBM
                                                    </button>
                                                </div>
                                                <div class="alert alert-info mb-0">Tidak ada data KBM untuk hari {{ $day }}.</div>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.row -->
            </div>
        </div>
    </div>
      <div class="modal fade" id="modal-default">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header bg-primary">
            <h4 class="modal-title" id="title" >Tambah KBM</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <form id="form-tambah-data" enctype="multipart/form-data">
            @csrf
            <div class="modal-body">
                <div class="form-group">
                    <label for="kelas">Kelas</label>
                    <select name="kode_kelas" id="kode_kelas" class="form-control">
                        <option value="{{ $kelas->id }}" selected>{{ $kelas->nama_kelas }}</option>
                    </select>
                    <div class="invalid-feedback" id="error-kode_kelas"></div>
                </div>
                 <div class="form-group">
                    <label for="hari">Hari</label>
                    <select id="hari_display" class="form-control" disabled>
                        <option value="" selected disabled>Pilih Hari</option>
                        @for ($hari = 1; $hari <= 7; $hari++)
                            <option value="{{ $hari }}">{{ hariNum($hari) }}</option>
                        @endfor
                    </select>
                    <input type="hidden" name="hari" id="hari">
                    <div class="invalid-feedback" id="error-hari"></div>
                </div>
                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="jam_mulai">Jam Mulai</label>
                            <input type="time" class="form-control" id="jam_mulai" name="jam_mulai" value="{{ old('jam_mulai') }}">
                            <div class="invalid-feedback" id="error-jam_mulai"></div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                         <div class="form-group">
                            <label for="jam_selesai">Jam Selesai</label>
                            <input type="time" class="form-control" id="jam_selesai" name="jam_selesai" value="{{ old('jam_selesai') }}">
                            <div class="invalid-feedback" id="error-jam_selesai"></div>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="mapel">Mapel</label>
                    <select name="kode_mapel" id="kode_mapel" class="form-control">
                        <option value="" selected disabled>Pilih Mapel</option>
                        @foreach ($mapel as $item)
                            <option value="{{ $item->id }}"> {{ $item->nama_mapel }}</option>
                        @endforeach
                    </select>
                    <div class="invalid-feedback" id="error-kode_mapel"></div>
                </div>
                <div class="form-group">
                    <label for="mapel">Guru</label>
                    <select name="nip" id="nip" class="form-control">
                        <option value="" selected disabled>Pilih Guru</option>
                        @foreach ($guru as $item)
                            <option value="{{ $item->nip }}"> [ {{ $item->nip }} ] - {{ $item->nama }}</option>
                        @endforeach
                    </select>
                    <div class="invalid-feedback" id="error-nip"></div>
                </div>
               
            </div>
            <div class="modal-footer justify-content-between">
              <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
              <button type="submit" class="btn btn-primary" id="simpan">Simpan</button>
            </div>
          </form>

        </div>
        <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->
    <!-- Modal Dinamis -->
    {{-- <div class="modal fade" id="modalShow" tabindex="-1" role="dialog" aria-labelledby="modalShowLabel" aria-hidden="true">
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
    </div> --}}
@endsection

@push('scripts')
   <script>
    $(document).ready(function() {
       
        $('#modal-default').on('show.bs.modal', function (event) {
            $(this).removeAttr('aria-hidden');
            
            var edit = false;
            var button = $(event.relatedTarget); 
            var id_kbm = button.data('id'); 
            var guru = button.data('guru');
            var mapel = button.data('mapel');
            var kelas = button.data('kelas');
            var hari = button.data('hari');
            var jam_mulai = button.data('jam_mulai');
            var jam_selesai = button.data('jam_selesai');

            if (id_kbm) {
                $('#title').text('Edit Mapel');
                $('#nip').val(guru);
                $('#kode_mapel').val(mapel);
                $('#kode_kelas').val(kelas);
                $('#hari_display').prop('disabled', false).attr('name', 'hari');
                $('#hari').remove(); // hapus input hidden jika mode edit
                $('#jam_mulai').val(jam_mulai);
                $('#jam_selesai').val(jam_selesai);
                $('#form-tambah-data').data('edit', true);
                $('#form-tambah-data').data('id', id_kbm);
            } else {
                $('#title').text('Tambah mapel');
                $('#nip').val('');
                $('#kode_mapel').val('');
                $('#hari_display').val(hari).prop('disabled', true);
                $('#hari').val(hari); // hidden input tetap diisi
                $('#jam_mulai').val('');
                $('#jam_selesai').val('');
                $('#form-tambah-data').data('edit', false);
                $('#form-tambah-data').data('id', null);
            }
        });

        $('#form-tambah-data').on('submit', function(e) {
            e.preventDefault();

            const formData  = new FormData(this);
            const isEdit    = $(this).data('edit');
            const idKBM     = $(this).data('id');

            const url = isEdit
              ? '/kbm/edit/' + idKBM
              : "{{ route('a.kbm.add') }}";

            const method    = 'POST'; 
            
            // Clear previous error messages
            $('.invalid-feedback').text('').hide();
            $.ajax({
                type: method,
                url: url,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: formData,
                processData: false,
                contentType: false,
                beforeSend: function() {
                    $('#simpan').attr('disabled', 'disabled');
                    $('#simpan').html(
                        '<i class="fa fa-spinner fa-spin mr-1"></i> Menyimpan');
                },
                complete: function() {
                    $('#simpan').removeAttr('disabled');
                    $('#simpan').html('Simpan');
                },
                success: function(response) {
                  if (response.status === 'warning') {
                        Toast.fire({
                            icon: 'warning',
                            title: response.message
                        });
                    } else {
                        $('#modal-default').modal('hide');
                        Toast.fire({
                            icon: 'success',
                            title: response.message
                        });
                        $('#form-tambah-data')[0].reset();
                        setTimeout(function() {
                            location.reload();
                        }, 2500);
                    }
                },
                error: function(xhr) {
                      if (xhr.responseJSON && xhr.responseJSON.errors) {
                          const errors = xhr.responseJSON.errors;

                          $.each(errors, function(key, value) {
                              let inputField = $('#' + key);
                              let errorFeedback = $('#error-' + key);

                              inputField.addClass('is-invalid'); // Tambahkan class is-invalid
                              errorFeedback.text(value[0]).show(); // Tampilkan pesan error
                          });

                      } else if (xhr.responseJSON && xhr.responseJSON.error) {
                          Swal.fire({
                              icon: 'error',
                              title: 'Gagal!',
                              text: xhr.responseJSON.error
                          });
                      } else {
                          Swal.fire({
                              icon: 'error',
                              title: 'Gagal!',
                              text: 'Kesalahan tidak terduga. Silakan coba lagi.'
                          });
                      }
                  }
            });

        });

        $('.btn-hapus').on('click', function (e) {

            var button = $(this); 
            var idKBM = button.data('id') 
            var url = "{{ route('a.kbm.delete', ':id') }}".replace(':id', idKBM);
            var method = 'DELETE';

            Swal.fire({
                title: "Hapus Data",
                text: "Apakah Anda yakin ingin menghapus data?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Ya, Hapus!"
                }).then((result) => {
                if (result.isConfirmed) {

                    $.ajax({
                        type: method,
                        url: url,
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: {
                            idKBM : idKBM
                        },
                        
                        processData: false,
                        contentType: false,
                        beforeSend: function() {
                            button.attr('disabled', 'disabled');
                            button.html(
                                '<i class="fa fa-spinner fa-spin mr-1"></i> Menghapus');
                        },
                        complete: function() {
                            button.removeAttr('disabled');
                            button.html('<i class="nav-icon fas fa-trash"></i> Hapus');
                        },

                        success: function(response) {
                            Toast.fire({
                                icon: 'success',
                                title: response.message
                            });
                            $('#form-tambah-data')[0].reset();
                            setTimeout(function() {
                                location.reload();
                            }, 2500);
                        },
                        error: function(xhr) {
                            if (xhr.responseJSON && xhr.responseJSON.errors) {
                                const errors = xhr.responseJSON.errors;

                            } else if (xhr.responseJSON && xhr.responseJSON.error) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal!',
                                    text: xhr.responseJSON.error
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal!',
                                    text: 'Kesalahan tidak terduga. Silakan coba lagi.'
                                });
                            }
                        }
                    });
                }
            });
        })
    });
    
    </script>
    {{-- <script>
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
    </script> --}}
@endpush
@extends('layout.main')
@section('content')
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><i class="nav-icon fas fa-clipboard"></i>  Presensi KBM</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12">
                        <h5 class="text-center"><b>Presensi Kegiatan Belajar Mengajar</b></h5>
                    </div>
                </div>
                 <a href="/kbm/detail/presensi/{{$kbm->id_kls_jurusan}}/{{$kbm->id}}" class="btn btn-warning btn-sm">
                    <i class="nav-icon fas fa-arrow-left"></i> Kembali </a>
                <div class="row mt-3">
                    <div class="col-lg-6">
                        <table class="table table-bordered table-sm">  
                            <tbody>
                                <tr>
                                    <td><b>Tahun Akademik</b></td>
                                    <td>
                                        {{ $kbm->tahunAjaran->tahun}} - {{ $kbm->tahunAjaran->semester == 1 ? 'Ganjil' : 'Genap'}}
                                    </td>
                                </tr>
                                <tr>
                                    <td><b>Mata Pelajaran</b></td>
                                    <td>
                                        {{ $kbm->mapel->nama_mapel }}
                                    </td>
                                </tr>
                                <tr>
                                    <td><b>Guru</b></td>
                                    <td>
                                        [ {{ $kbm->guru->nip }} ] - {{ $kbm->guru->nama }}  
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-lg-6">
                        <table class="table table-bordered table-sm">
                            <tbody>
                                <tr>
                                    <td><b>Kelas</b> </td>
                                    <td>
                                        {{ $kbm->kelas->nama_kelas }} -
                                        [ {{ $kbm->kelas->jurusan->nama_jurusan }} ]
                                    </td>
                                </tr>
                                <tr>
                                    <td><b>Pertemuan Ke</b></td>
                                    <td>
                                        {{ $presensi->pertemuan_ke }}
                                    </td>
                                </tr>
                                <tr>
                                    <td><b>Tanggal</b></td>
                                    <td>
                                        {{ formatTanggalIndonesia($presensi->tanggal, 'l, d F Y') }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-lg-6">
                        <h5 class="text-center"><b>QR Code Presensi</b></h5>                        
                        <br>
                        <p class="text-center">Scan QR Code di bawah ini untuk melakukan presensi</p>
                        <div class="text-center">
                            <center>
                                {!! DNS2D::getBarcodeHTML( strval($presensi->id) , 'QRCODE') !!}
                            </center>

                            @if ($presensi->ket == 'A')
                                <div class="text-center mt-4">
                                    <span class="badge badge-success">Presensi Aktif</span>
                                    <p>
                                        <b>Waktu : </b> <span id="countDown"></span> Menit
                                    </p> 
                                    <button type="button" class="btn btn-xs btn-danger" onclick="tutupPresensi()">Tutup Presensi</button>
                                </div>
                                <script>
                                    var waktu = 600; // 10 menit = 600 detik

                                    var timer = setInterval(function() {
                                        var menit = Math.floor(waktu / 60);
                                        var detik = waktu % 60;
                                        document.getElementById('countDown').innerHTML = (menit < 10 ? '0' : '') + menit + ':' + (detik < 10 ? '0' : '') + detik;

                                        waktu--;

                                        if (waktu < 0) {
                                            clearInterval(timer);
                                            tutupPresensiAjax();
                                        }
                                    }, 1000);

                                    function tutupPresensi() {
                                        Swal.fire({
                                            title: 'Tutup Presensi',
                                            text: "Apakah Anda yakin ingin menutup presensi ini?",
                                            icon: 'warning',
                                            showCancelButton: true,
                                            confirmButtonColor: '#3085d6',
                                            cancelButtonColor: '#d33',
                                            confirmButtonText: 'Ya, tutup presensi!'
                                        }).then((result) => {
                                            if (result.isConfirmed) {
                                                tutupPresensiAjax();
                                            }
                                        });
                                    }   
                                </script>
                            @else
                                <div class="text-center mt-4">
                                    <span class="badge badge-danger">Presensi Tidak Aktif</span> <br>
                                    <button type="button" class="btn btn-xs btn-success" onclick="aktifkan()">Aktifkan</button>
                                </div>
                            @endif
                        </div>
                        <br>
                    </div>
                    <div class="col-lg-6">
                         <div class="card">
                            <div class="card-header">
                                <h3 class="card-title"><i class="nav-icon fas fa-users"></i> Daftar Siswa</h3>
                                <div class="card-tools">
                                    <button type="button" class="btn btn-tool" data-card-widget="card-refresh"  id="refresh-log-presensi">
                                        <i class="fas fa-sync-alt"></i>
                                    </button>
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                </div>
                                <!-- /.card-tools -->
                            </div>
                            <div class="card-body">
                                <h6><b>Total siswa = {{ $logPresensi->count() }}</b></h6>
                                <div id="log-presensi-table">
                                    @include('admin.kbm._table_log_presensi', ['logPresensi' => $logPresensi])
                                </div>
                            </div>
                            <!-- /.card-body -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modal-default">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h4 class="modal-title" id="title">Kehadiran</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="form-update-data" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="kelas">Kehadiran</label>
                            <input type="hidden" name="id" id="id">
                            <select name="status" id="status" class="form-control">
                                <option value="" selected disabled>Pilih Status</option>
                                <option value="Hadir">Hadir</option>
                                <option value="Izin">Izin</option>
                                <option value="Sakit">Sakit</option>
                                <option value="Alpa">Alpa</option>
                            </select>
                            <div class="invalid-feedback" id="error-status"></div>
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

    <script>

        $('#modal-default').on('show.bs.modal', function (event) {
            $(this).removeAttr('aria-hidden');
            
            var button = $(event.relatedTarget); 
            var id = button.data('id'); 
            var status = button.data('status'); 

            $('#id').val(id);
            $('#status').val(status);
        });

        $('#form-update-data').on('submit', function(e) {
            e.preventDefault();

            const formData  = new FormData(this);
            const url = "{{ route('a.kbm.presensi.update') }}";
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
                        // update the table
                        const badge = $('#badge-status-' + response.id);
                        
                        badge.removeClass() // hapus semua class lama
                        badge.addClass('badge ' + response.badge_class) // tambah class baru
                        badge.html(response.status_presensi + ' <i class="nav-icon fas fa-chevron-down"></i>');

                        $('#form-update-data')[0].reset();
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

        $('[data-card-widget="card-refresh"]').on('click', function () {
            const id = '{{ $id }}'; // pastikan variabel ini tersedia

            $.ajax({
                url: `/kbm/presensi/log/${id}`,
                method: 'GET',
                beforeSend: function () {
                    $('#log-presensi-table').html('<div class="text-center py-3"><i class="fas fa-spinner fa-spin"></i> Memuat data...</div>');
                },
                success: function (res) {
                    $('#log-presensi-table').html(res.html);
                },
                error: function () {
                    $('#log-presensi-table').html('<div class="text-danger">Gagal memuat data presensi.</div>');
                }
            });
        });

        
    </script>
    <script>
        function tutupPresensiAjax() {
            $.ajax({
                url: "{{ route('a.kbm.presensi.tutup', $presensi->id) }}",
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (response) {
                    location.reload();
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
        }

        function aktifkan() {
                 Swal.fire({
                    title: 'Tutup Presensi',
                    text: "Apakah Anda yakin ingin mengaktifkan presensi ini?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, Aktifkan presensi!'
                }).then((result) => {
                    if (result.isConfirmed) {
                         $.ajax({
                            url: "{{ route('a.kbm.presensi.aktifkan', $presensi->id) }}",
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function (response) {
                                location.reload();
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
                    }
                });
            }
    </script>
@endsection
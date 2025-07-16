<?php

use App\Http\Controllers\Admin\AdminDashController;
use App\Http\Controllers\Admin\APresensiController;
use App\Http\Controllers\Admin\GuruController;
use App\Http\Controllers\Admin\JurusanController;
use App\Http\Controllers\Admin\KBMController;
use App\Http\Controllers\Admin\KelasJurusanController;
use App\Http\Controllers\Admin\MapelController;
use App\Http\Controllers\Admin\PeriodeController;
use App\Http\Controllers\Admin\SiswaController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\GPresensiController;
use App\Http\Controllers\Guru\GKBMController;
use App\Http\Controllers\Guru\GuruDashController;
use App\Http\Controllers\Siswa\PresensiController;
use App\Http\Controllers\Siswa\SiswaDashController;
use Illuminate\Support\Facades\Route;


Route::get('/', [AuthController::class, 'index'])->middleware('guest')->name('login');
Route::post('/', [AuthController::class, 'verify'])->name('verify');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
Route::middleware(['auth:admin,guru,siswa'])->group(function () {
    Route::get('/ganti_password', [AuthController::class, 'ganti_password'])->name('password');
    Route::post('/ganti_password/{id}', [AuthController::class, 'update_password'])->name('password.update');
});

Route::group(['middleware' =>'auth:admin'],function () {
    Route::get('/dashboard', [AdminDashController::class, 'index'])->name('a.dashboard');

    Route::get('/periode', [PeriodeController::class, 'index'])->name('a.periode');
    Route::post('/periode/add', [PeriodeController::class, 'store'])->name('a.periode.add');
    Route::post('/periode/aktifkan', [PeriodeController::class, 'aktifkan'])->name('a.periode.aktif');
    Route::delete('/periode/delete/{id}', [PeriodeController::class, 'destroy'])->name('a.periode.delete');

    Route::get('/jurusan', [JurusanController::class, 'index'])->name('a.jurusan');
    Route::post('/jurusan/add', [JurusanController::class, 'store'])->name('a.jurusan.add');
    Route::post('/jurusan/edit/{id}', [JurusanController::class, 'update'])->name('a.jurusan.edit');
    Route::delete('/jurusan/delete/{id}', [JurusanController::class, 'destroy'])->name('a.jurusan.delete');
    Route::get('/jurusan/export', [JurusanController::class, 'export'])->name('a.jurusan.export');

    Route::get('/mapel', [MapelController::class, 'index'])->name('a.mapel');
    Route::post('/mapel/add', [MapelController::class, 'store'])->name('a.mapel.add');
    Route::post('/mapel/edit/{id}', [MapelController::class, 'update'])->name('a.mapel.edit');
    Route::delete('/mapel/delete/{id}', [MapelController::class, 'destroy'])->name('a.mapel.delete');

    Route::get('/siswa', [SiswaController::class, 'index'])->name('a.siswa');
    Route::post('/siswa/add', [SiswaController::class, 'store'])->name('a.siswa.add');
    Route::post('/siswa/edit/{id}', [SiswaController::class, 'update'])->name('a.siswa.edit');
    Route::delete('/siswa/delete/{id}', [SiswaController::class, 'destroy'])->name('a.siswa.delete');
    Route::post('/siswa/import', [SiswaController::class, 'import'])->name('a.siswa.import');
    Route::get('/siswa/download', [SiswaController::class, 'download'])->name('a.siswa.download');
    Route::get('/siswa/export', [SiswaController::class, 'export'])->name('a.siswa.export');
    Route::post('/siswa/reset_pw/{nis}', [SiswaController::class, 'resetPassword'])->name('a.siswa.reset_pw');

    Route::get('/kelas_jurusan', [KelasJurusanController::class, 'index'])->name('a.kelas');
    Route::post('/kelas_jurusan/add', [KelasJurusanController::class, 'store'])->name('a.kelas.add');
    Route::post('/kelas_jurusan/edit/{id}', [KelasJurusanController::class, 'update'])->name('a.kelas.edit');
    Route::delete('/kelas_jurusan/delete/{id}', [KelasJurusanController::class, 'destroy'])->name('a.kelas.delete');
    Route::get('/kelas_jurusan/detail/{id}', [KelasJurusanController::class, 'detail'])->name('a.kelas.detail');
    Route::post('/kelas_jurusan/add/{id}', [KelasJurusanController::class, 'addSiswa'])->name('a.kelas.add.siswa');
    Route::get('/kelas_jurusan/download', [KelasJurusanController::class, 'download'])->name('a.kelas.download');
    Route::post('/kelas_jurusan/import/{id}', [KelasJurusanController::class, 'import'])->name('a.kelas.import');

    Route::get('/guru', [GuruController::class, 'index'])->name('a.guru');
    Route::post('/guru/add', [GuruController::class, 'store'])->name('a.guru.add');
    Route::post('/guru/edit/{id}', [GuruController::class, 'update'])->name('a.guru.edit');
    Route::delete('/guru/delete/{id}', [GuruController::class, 'destroy'])->name('a.guru.delete');
    Route::post('/guru/import', [GuruController::class, 'import'])->name('a.guru.import');
    Route::get('/guru/download', [GuruController::class, 'download'])->name('a.guru.download');
    Route::post('/guru/reset_pw/{nip}', [GuruController::class, 'resetPassword'])->name('a.guru.reset_pw');

    Route::get('/kbm', [KBMController::class, 'index'])->name('a.kbm');
    Route::get('/kbm/detail/{id}', [KBMController::class, 'detail'])->name('a.kbm.detail');
    Route::get('/kbm/detail/presensi/{id_kelas}/{id}', [KBMController::class, 'detail_presensi'])->name('a.kbm.detail.presensi');
    Route::post('/kbm/add', [KBMController::class, 'store'])->name('a.kbm.add');
    Route::post('/kbm/edit/{id}', [KBMController::class, 'update'])->name('a.kbm.edit');
    Route::delete('/kbm/delete/{id}', [KBMController::class, 'destroy'])->name('a.kbm.delete');
    Route::get('/kbm/create/presensi/{id}', [KBMController::class, 'create'])->name('a.kbm.create');
    Route::post('/kbm/create/presensi', [KBMController::class, 'store_presensi'])->name('a.kbm.create.presensi');
    Route::get('/kbm/presensi/{id}', [KBMController::class, 'presensi'])->name('a.kbm.presensi');
    Route::post('/kbm/presensi/update', [KBMController::class, 'update_presensi'])->name('a.kbm.presensi.update');
    Route::get('/kbm/presensi/log/{id}', [KBMController::class, 'getLogPresensi'])->name('a.kbm.presensi.log');
    Route::post('/kbm/presensi/tutup/{id}', [KBMController::class, 'tutupPresensi'])->name('a.kbm.presensi.tutup');
    Route::post('/kbm/presensi/aktifkan/{id}', [KBMController::class, 'aktifkanPresensi'])->name('a.kbm.presensi.aktifkan');

    Route::get('/presensi', [APresensiController::class, 'index'])->name('a.presensi');
    Route::get('/presensi/kbm/{id}', [APresensiController::class, 'kbm'])->name('a.presensi.kbm');
    Route::get('/presensi/kbm/create/{id}', [APresensiController::class, 'create'])->name('a.presensi.kbm.create');
    Route::get('/presensi/kbm/pdf/{id}', [APresensiController::class, 'pdf'])->name('a.presensi.kbm.pdf');

});

Route::group(['middleware' =>'auth:guru'],function () {
    Route::get('/guru/dashboard', [GuruDashController::class, 'index'])->name('g.dashboard');

    Route::get('/guru/kbm', [GKBMController::class, 'index'])->name('g.kbm');
    Route::get('/guru/kbm/create/presensi/{id}', [GKBMController::class, 'create'])->name('g.kbm.create');
    Route::get('/guru/kbm/detail/presensi/{id}', [GKBMController::class, 'detail_presensi'])->name('g.kbm.detail.presensi');
    Route::post('/guru/kbm/create/presensi', [GKBMController::class, 'store_presensi'])->name('g.kbm.create.presensi');
    Route::get('/guru/kbm/presensi/{id}', [GKBMController::class, 'presensi'])->name('g.kbm.presensi');
    Route::post('/guru/kbm/presensi/update', [KBMController::class, 'update_presensi'])->name('g.kbm.presensi.update');
    Route::get('/guru/kbm/presensi/log/{id}', [KBMController::class, 'getLogPresensi'])->name('g.kbm.presensi.log');
    Route::post('/guru/kbm/presensi/tutup/{id}', [KBMController::class, 'tutupPresensi'])->name('g.kbm.presensi.tutup');
    Route::post('/guru/kbm/presensi/aktifkan/{id}', [KBMController::class, 'aktifkanPresensi'])->name('g.kbm.presensi.aktifkan');

    Route::get('/guru/presensi', [GPresensiController::class, 'index'])->name('g.presensi');
    Route::get('/guru/presensi/kbm/{id}', [GPresensiController::class, 'kbm'])->name('g.presensi.kbm');
    Route::get('/guru/presensi/kbm/create/{id}', [GPresensiController::class, 'create'])->name('g.presensi.kbm.create');
    Route::get('/guru/presensi/kbm/pdf/{id}', [GPresensiController::class, 'pdf'])->name('g.presensi.kbm.pdf');
});

Route::group(['middleware' =>'auth:siswa'],function () {
    Route::get('/siswa/dashboard', [SiswaDashController::class, 'index'])->name('s.dashboard');
    Route::get('/siswa/presensi', [PresensiController::class, 'index'])->name('s.presensi');
    Route::post('/siswa/presensi/{id}', [PresensiController::class, 'presensi'])->name('s.presensi.store');
});


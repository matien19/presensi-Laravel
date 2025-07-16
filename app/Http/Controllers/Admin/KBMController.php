<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GuruModel;
use App\Models\KBMModel;
use App\Models\KelasJurusanModel;
use App\Models\LogPresensiModel;
use App\Models\MapelModel;
use App\Models\PeriodeModel;
use App\Models\PresensiModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class KBMController extends Controller
{
     public function index()
    {
        $taAktif = PeriodeModel::where('status', 'A')->first();
        
        $kelas = KelasJurusanModel::with('kbm')
            ->where('id_ta', $taAktif->id)
            ->orderBy('nama_kelas', 'asc')
            ->get();

        return view('admin.kbm.kbm2', compact('taAktif','kelas'));
    }

     public function detail($id)
    {
        $kelas = KelasJurusanModel::findOrFail($id);

        $kbm = KBMModel::with(['mapel', 'guru', 'tahunAjaran', 'kelas'])
            ->where('id_kls_jurusan', $id)
            ->get()
            ->groupBy('hari');

        $guru = GuruModel::all();
        $mapel = MapelModel::all();
        
        return view('admin.kbm.detail', compact('kelas', 'kbm', 'guru', 'mapel','id'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_mapel'  => 'required|exists:mapel,id',
            'kode_kelas'  => 'required|exists:ta_kelas_jurusan,id',
            'nip'       => 'required|exists:guru,nip',
            'hari'      => 'required',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required',
           ]);

        // Validate that the start time is before the end time
        if ($request->jam_mulai >= $request->jam_selesai) {
            return response()->json([
                'status' => 'warning',
                'message' => 'Jam mulai harus sebelum jam selesai.'
            ]);
        }

        // Check if the period already exists
        $existingKBM = KBMModel::where('hari', $request->hari)
            ->where('jam_mulai', $request->jam_mulai)
            ->where('jam_selesai', $request->jam_selesai)
            ->where('id_mapel', $request->kode_mapel)
            ->where('id_kls_jurusan', $request->kode_kelas)
            ->where('nip', $request->nip)
            ->first();

        if ($existingKBM) {
            return response()->json([
                'status' => 'warning',
                'message' => 'Data KBM sudah ada / jadwak KBM bertabrakan.'
            ]); 
        }
    
        $taAktif = PeriodeModel::where('status', 'A')->first();
        if (!$taAktif) {
            return response()->json([
                'status' => 'warning',
                'message' => 'Tidak ada tahun ajaran aktif.'
            ]);
        }

        KBMModel::create([
            'hari' => $request->hari,
            'jam_mulai' => $request->jam_mulai,
            'jam_selesai' => $request->jam_selesai,
            'id_mapel' => $request->kode_mapel,
            'id_kls_jurusan' => $request->kode_kelas,
            'nip' => $request->nip,
            'id_ta' => $taAktif->id,
        ]);
        
        return response()->json([
            'status' => 'success',
            'message' => 'Data KBM telah berhasil ditambahkan.'
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'kode_mapel'  => 'required|exists:mapel,id',
            'kode_kelas'  => 'required|exists:ta_kelas_jurusan,id',
            'nip'       => 'required|exists:guru,nip',
            'hari'      => 'required',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required',
        ]);

        // Validate that the start time is before the end time
        if ($request->jam_mulai >= $request->jam_selesai) {
            return response()->json([
                'status' => 'error',
                'message' => 'Jam mulai harus sebelum jam selesai.'
            ]);
        }
         
        KBMModel::where('id', $id)->update([
            'hari' => $request->hari,
            'jam_mulai' => $request->jam_mulai,
            'jam_selesai' => $request->jam_selesai,
            'id_mapel' => $request->kode_mapel,
            'id_kls_jurusan' => $request->kode_kelas,
            'nip' => $request->nip,
        ]);
        
        return response()->json([
            'status' => 'success',
            'message' => 'Data KBM telah berhasil diubah.'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $kbm      = KBMModel::where('id', $id);
        if ($kbm->exists()) {
            $kbm->delete();
            return response()->json([
                'status' => 'success',
                'message' => 'Data KBM berhasil dihapus.'
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Data KBM tidak ditemukan.'
            ]);
        }
    }

    public function create(Request $request, string $id)
    {
        $presensi = PresensiModel::where('id_kbm', $id)->get();
        return view('admin.kbm._form', compact('presensi', 'id'));
    }

    public function store_presensi(Request $request)
    {
        $request->validate([
            'id' => 'required|string',
        ]);

        $pertemuanKe = $request->id;
        // Jika user memilih pertemuan baru
        if ($request->id === 'baru') {
            $lastPertemuan = PresensiModel::where('id_kbm', $request->id_kbm)->count(); 
            
            $pertemuanKe = $lastPertemuan + 1;

            // Simpan data presensi baru
            $presensi = PresensiModel::create([
                'pertemuan_ke' => $pertemuanKe,
                'tanggal' => now(),
                'id_kbm' => $request->id_kbm,
                'ket' => 'A',
                // tambahkan field lain jika perlu
            ]);

            $kbm = KBMModel::with('kelas.kelasSiswa')->findOrFail($request->id_kbm);
            $siswaKelas = $kbm->kelas->kelasSiswa;

            // Log::info($siswaKelas);
            
            $insertData = [];
            foreach ($siswaKelas as $siswa) {
                $insertData[] = [
                    'id_presensi' => $presensi->id,
                    'nis' => $siswa->nis,
                    'status' => 'Alpa',
                ];
            }

            LogPresensiModel::insert($insertData);

            return response()->json([
                'redirect_url' => route('a.kbm.presensi', $presensi->id)
            ]);
        }

        return response()->json([
            'redirect_url' => route('a.kbm.presensi', $request->id)
        ]);
    }

    public function presensi( string $id)
    {
        $presensi = PresensiModel::findOrFail($id);
        Log::info($presensi);
        
        $kbm = KBMModel::with('kelas.jurusan', 'tahunAjaran', 'mapel', 'guru')->findOrFail($presensi->id_kbm);
        $logPresensi = LogPresensiModel::where('id_presensi', $id)
        ->with('siswa')
        ->get();
        
        return view('admin.kbm.presensi', compact('presensi', 'id', 'kbm', 'logPresensi'));
    }

    public function update_presensi(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:log_presensi,id',
            'status' => 'required|in:Hadir,Alpa,Izin,Sakit',
        ]);

        $logPresensi = LogPresensiModel::where('id', $request->id)->first();
        if ($logPresensi) {
            $logPresensi->update(['status' => $request->status]);
            return response()->json([
                'status' => 'success',
                'message' => 'Status presensi berhasil diperbarui.',
                'id' => $request->id,
                'status_presensi' => $request->status,
                'badge_class' => 'badge-' . warnaStatus($request->status),
            ]);
        } else {
            return response()->json([
                'status' => 'warning',
                'message' => 'Data presensi tidak ditemukan.'
            ]);
        }
    }

    public function getLogPresensi($id)
    {
        $logPresensi = LogPresensiModel::with('siswa')->where('id_presensi', $id)->get();

        $html = view('guru.kbm._table_log_presensi', compact('logPresensi'))->render();

        return response()->json(['html' => $html]);
    }

    public function tutupPresensi($id)
    {
        $presensi = PresensiModel::findOrFail($id);
        $presensi->update(['ket' => 'T']);

        return response()->json([
            'status' => 'success',
            'message' => 'Presensi telah ditutup.',
        ]);
    }

    public function aktifkanPresensi($id)
    {
        $presensi = PresensiModel::findOrFail($id);
        $presensi->update(['ket' => 'A']);

        return response()->json([
            'status' => 'success',
            'message' => 'Presensi telah diaktifkan.',
        ]);
    }
    public function detail_presensi($id_kelas, $id)
    {
        $kelas = KelasJurusanModel::with(['kelasSiswa'])->findOrFail($id_kelas);
        $pertemuan = PresensiModel::where('id_kbm', $id)->get();
        $kbm = KBMModel::with(['mapel', 'guru', 'tahunAjaran', 'kelas'])
            ->where('id', $id)
            ->first();
        return view('admin.kbm.detail_presensi', compact('id_kelas', 'id','kelas','pertemuan', 'kbm'));

    }
}
 
<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\KBMModel;
use App\Models\KelasJurusanModel;
use App\Models\LogPresensiModel;
use App\Models\PeriodeModel;
use App\Models\PresensiModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GKBMController extends Controller
{
    public function index()
    {
        $taAktif = PeriodeModel::where('status', 'A')->first();
        $nip = Auth::user()->username;

        $kbm = KBMModel::where('id_ta', $taAktif->id)
        ->where('nip', $nip)
        ->with(['mapel', 'kelas', 'guru'])
        ->orderBy('hari', 'asc')
        ->get();

        return view('guru.kbm.kbm2', compact('kbm','taAktif'));
    }

    public function create(Request $request, string $id)
    {
        $presensi = PresensiModel::where('id_kbm', $id)->get();
        return view('guru.kbm._form', compact('presensi', 'id'));
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
                'redirect_url' => route('g.kbm.presensi', $presensi->id)
            ]);
        }

        return response()->json([
            'redirect_url' => route('g.kbm.presensi', $request->id)
        ]);
    }

     public function presensi( string $id)
    {
        $presensi = PresensiModel::findOrFail($id);
        // Log::info($presensi);
        
        $kbm = KBMModel::with('kelas.jurusan', 'tahunAjaran', 'mapel', 'guru')->findOrFail($presensi->id_kbm);
        $logPresensi = LogPresensiModel::where('id_presensi', $id)
        ->with('siswa')
        ->get();
        
        return view('guru.kbm.presensi', compact('presensi', 'id', 'kbm', 'logPresensi'));
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

    public function detail_presensi($id)
    {
        // $kelas = KelasJurusanModel::with(['kelasSiswa'])->findOrFail($id_kelas);
        $pertemuan = PresensiModel::where('id_kbm', $id)->get();
        $kbm = KBMModel::with(['mapel', 'guru', 'tahunAjaran', 'kelas'])
            ->where('id', $id)
            ->first();
        return view('guru.kbm.detail_presensi', compact('id','pertemuan', 'kbm'));

    }
}

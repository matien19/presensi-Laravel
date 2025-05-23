<?php

namespace App\Http\Controllers\Admin;

use App\Exports\JurusanExport;
use App\Http\Controllers\Controller;
use App\Models\JurusanModel;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class JurusanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $jurusan = JurusanModel::All();
        return view('admin.jurusan', compact('jurusan'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'jurusan' => 'required|string|max:255',
            'kode_jurusan' => 'required|string|max:10',
        ]);
        
        // Check if the period already exists
        $existingPeriod = JurusanModel::where('nama_jurusan', $request->jurusan)
            ->orWhere('kode_jurusan', $request->kode_jurusan) 
            ->first();
        
        if ($existingPeriod) {
            return response()->json([
                'status' => 'warning',
                'message' => 'Jurusan sudah ada.'
            ]); // HTTP 409: Conflict
        }
        
        JurusanModel::create([
            'kode_jurusan' => $request->kode_jurusan,
            'nama_jurusan' => $request->jurusan,
        ]);
    
        return response()->json([
            'status' => 'success',
            'message' => 'Data Jurusan telah berhasil ditambahkan.'
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'jurusan' => 'required|string|max:255',
        ]);

        
        // Check if the period already exists
        $existingPeriod = JurusanModel::where('nama_jurusan', $request->jurusan)
            ->first();
        
        if ($existingPeriod) {
            return response()->json([
                'status' => 'warning',
                'message' => 'Jurusan sudah ada.'
            ]); // HTTP 409: Conflict
        }

        JurusanModel::where('kode_jurusan', $id)->update([
            'nama_jurusan' => $request->jurusan,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Data Jurusan telah berhasil diubah.'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $jurusan = JurusanModel::where('kode_jurusan', $id);
        if ($jurusan) {
            $jurusan->delete();
            return response()->json([
                'status' => 'success',
                'message' => 'Data jurusan berhasil dihapus.'
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Data jurusan tidak ditemukan.'
            ]);
        }
    }

    public function export() 
    {
        return Excel::download(new JurusanExport, 'data_jurusan.xlsx');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KelasSiswaModel extends Model
{
    use HasFactory;

    protected $table = 'kelas_siswa';
    protected $fillable = [
        "id_kls_jurusan",
        "nis",
    ];

    // Relasi ke model Siswa berdasarkan nis
    public function siswa()
    {
        return $this->belongsTo(SiswaModel::class, 'nis', 'nis');
    }
}

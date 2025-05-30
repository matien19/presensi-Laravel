<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KelasJurusanModel extends Model
{
    use HasFactory;

    protected $table = 'ta_kelas_jurusan';
    protected $fillable = [
        "nama_kelas",
        "kode_jurusan",
        "kode_kelas",
        "id_ta",
    ];

    public function jurusan()
    {
        return $this->belongsTo(JurusanModel::class, 'kode_jurusan', 'kode_jurusan');
    }
    public function tahunAjaran()
    {
        return $this->belongsTo(PeriodeModel::class, 'id_ta', 'id');
    }
    public function kelas()
    {
        return $this->belongsTo(KelasModel::class, 'kode_kelas', 'kode_kelas');
    }
    public function kelasSiswa()
    {
        return $this->hasMany(KelasSiswaModel::class, 'id_kls_jurusan', 'id');
    }

    public function kbm()
    {
        return $this->hasMany(KBMModel::class, 'id_kls_jurusan', 'id');
    }

}

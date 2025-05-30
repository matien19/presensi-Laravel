<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SiswaModel extends Model
{
    use HasFactory;

    protected $table = 'siswa';
    protected $fillable = [
        "nis",
        "nama",
        "no_hp",
        "kelamin",
        "alamat",
        "kode_jurusan",
    ];
    public function jurusan()
    {
        return $this->belongsTo(JurusanModel::class, 'kode_jurusan', 'kode_jurusan');
    }
}

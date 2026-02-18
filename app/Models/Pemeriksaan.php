<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pemeriksaan extends Model
{
    protected $table = 'pemeriksaans';

    protected $fillable = [
        'pegawai_id',
        'tanggal_pemeriksaan',
        'puasa',
        'tinggi_badan',
        'berat_badan',
        'sistolik',
        'diastolik',
        'nadi',
        'nilai_glukometer',
        'parameter_gula',
        'kolesterol_total',
        'asam_urat',
        'catatan_dokter',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_pemeriksaan' => 'date',
            'puasa' => 'boolean',
            'tinggi_badan' => 'decimal:1',
            'berat_badan' => 'decimal:1',
            'sistolik' => 'integer',
            'diastolik' => 'integer',
            'nadi' => 'integer',
            'nilai_glukometer' => 'decimal:1',
            'kolesterol_total' => 'decimal:1',
            'asam_urat' => 'decimal:1',
        ];
    }

    /**
     * Relasi ke Pegawai
     */
    public function pegawai()
    {
        return $this->belongsTo(\App\Models\Pegawai::class, 'pegawai_id');
    }
}

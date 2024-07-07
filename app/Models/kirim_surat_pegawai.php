<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class kirim_surat_pegawai extends Model
{
    use HasFactory;
    protected $table = 'kirim_surat_pegawais';

    protected $fillable = [
        'judul',
        'deskripsi',
        'status_letter',
        'letter_id',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function scopeStatusLetter($query, $statusLetter)
    {
        return $query->where('status_letter', $statusLetter);
    }

    public function letter()
    {
        return $this->belongsTo(Surat::class);
    }
}

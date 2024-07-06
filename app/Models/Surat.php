<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Surat extends Model
{
    use HasFactory;
    protected $table = 'surats';

    protected $fillable = [
        'no_surat',
        'no_surat_idx',
        'file',
        'nama_file',
        'tanggal_upload_surat',
        'jam_upload_surat',
        'nama_pengirim',
        'email_pengirim',
        'instansi_pengirim',
        'no_telp_pengirim',
        'status',
        'status_letter',
        'approved_by',
        'forwarded_to',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    // Relasi dengan tabel users (untuk forwarded_to)
    public function forwardedTo()
    {
        return $this->belongsTo(User::class, 'forwarded_to');
    }

    // Custom query scope untuk status surat
    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    // Custom query scope untuk status letter
    public function scopeStatusLetter($query, $statusLetter)
    {
        return $query->where('status_letter', $statusLetter);
    }
}

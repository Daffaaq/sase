<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class permintaan_surat extends Model
{
    use HasFactory;

    protected $table = 'permintaan_surats';

    protected $fillable = [
        'request_title',
        'request_content',
        'status',
        'requested_by',
        'approved_by',
        'generated_letter_id',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    // Relasi dengan tabel users (untuk requested_by)
    public function requestedBy()
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    // Relasi dengan tabel users (untuk approved_by)
    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    // Relasi dengan tabel surats (untuk generated_letter_id)
    public function generatedLetter()
    {
        return $this->belongsTo(Surat::class, 'generated_letter_id');
    }

    // Custom query scope untuk status permintaan surat
    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }
}

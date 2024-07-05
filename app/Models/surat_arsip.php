<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class surat_arsip extends Model
{
    use HasFactory;

    protected $table = 'surat_arsips';

    protected $fillable = [
        'letter_id',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    // Relasi dengan tabel surats (untuk letter_id)
    public function surat()
    {
        return $this->belongsTo(Surat::class, 'letter_id');
    }
}

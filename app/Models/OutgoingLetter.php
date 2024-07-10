<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class OutgoingLetter extends Model
{
    use HasFactory;

    protected $table = 'outgoing_letters';

    protected $fillable = [
        'reference_letter_id',
        'nomer_surat_keluar',
        'nomer_surat_keluark_idx',
        'tanggal_surat_keluar',
        'nama_penerima',
        'email_penerima',
        'keterangan',
        'file',
        'category_surat_id',
        'status',
        'created_by',
        'updated_by',
    ];

    protected $dates = [
        'tanggal_surat_keluar',
        'created_at',
        'updated_at',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->uuid = Str::uuid();
        });
    }

    public function category()
    {
        return $this->belongsTo(CategoryOutgoingLetter::class, 'category_surat_id');
    }

    public function referenceLetter()
    {
        return $this->belongsTo(IncomingLetter::class, 'reference_letter_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function editor()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}

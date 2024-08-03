<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class IncomingLetter extends Model
{
    use HasFactory;

    protected $table = 'incoming_letters';

    protected $fillable = [
        'nomer_surat_masuk',
        'nomer_surat_masuk_idx',
        'tanggal_surat_masuk',
        'nama_pengirim',
        'email_pengirim',
        'keterangan',
        'file',
        'category_surat_id',
        'sifat_surat_id',
        'status',
        'disposition_status',
        'created_by',
        'updated_by',
    ];

    protected $dates = [
        'tanggal_surat_masuk',
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
        return $this->belongsTo(CategoryIncomingLetter::class, 'category_surat_id');
    }

    public function archive()
    {
        return $this->hasOne(ArchiveIncomingLetter::class, 'letter_incoming_id');
    }

    public function sifat()
    {
        return $this->belongsTo(SifatIncomingLetter::class, 'sifat_surat_id');
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

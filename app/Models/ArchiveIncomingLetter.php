<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ArchiveIncomingLetter extends Model
{
    use HasFactory;

    protected $table = 'archive_incoming_letters';

    protected $fillable = [
        'letter_incoming_id',
        'kode_arsip_incoming',
        'category_incoming_id',
        'date_archive_incoming',
        'created_by',
        'updated_by',
    ];

    protected $dates = [
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

    public function incomingLetter()
    {
        return $this->belongsTo(IncomingLetter::class, 'letter_incoming_id');
    }

    public function category()
    {
        return $this->belongsTo(CategoryArchiveIncomingLetter::class, 'category_incoming_id');
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

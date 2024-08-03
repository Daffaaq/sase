<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ArchiveOutgoingLetter extends Model
{
    use HasFactory;

    protected $table = 'archive_outgoing_letters';

    protected $fillable = [
        'uuid',
        'letter_outgoing_id',
        'category_Outgoing_id',
        'date_archive_outgoing',
        'kode_arsip_outgoing',
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

    public function outgoingLetter()
    {
        return $this->belongsTo(OutgoingLetter::class, 'letter_outgoing_id');
    }
    public function category()
    {
        return $this->belongsTo(CategoryArchiveOutgoingLetter::class, 'category_Outgoing_id');
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

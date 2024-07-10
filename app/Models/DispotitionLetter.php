<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class DispotitionLetter extends Model
{
    use HasFactory;

    protected $table = 'disposition_letters';

    protected $fillable = [
        'uuid',
        'letter_id',
        'user_id',
        'Tugas',
        'Tanggal Disposisi',
        'created_by',
        'updated_by',
    ];

    protected $dates = [
        'Tanggal Disposisi',
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
        return $this->belongsTo(IncomingLetter::class, 'letter_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
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

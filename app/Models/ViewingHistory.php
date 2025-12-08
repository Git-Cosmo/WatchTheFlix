<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ViewingHistory extends Model
{
    use HasFactory;

    protected $table = 'viewing_history';

    protected $fillable = [
        'user_id',
        'media_id',
        'progress',
        'completed',
        'last_watched_at',
    ];

    protected $casts = [
        'progress' => 'integer',
        'completed' => 'boolean',
        'last_watched_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function media()
    {
        return $this->belongsTo(Media::class);
    }
}

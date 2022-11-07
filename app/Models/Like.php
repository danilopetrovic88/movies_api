<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'movie_id',
        'like'
    ];

    const LIKED = 1;
    const DISLIKED = 2;
    const NONE = 3;

    const LIKE_STATES = [ 
        self::LIKED => 'liked',
        self::DISLIKED => 'disliked',
        self::NONE => 'none'
    ];

    public function movie() 
    {
        return $this->belongsTo(Movie::class);
    }

    public function user() 
    {
        return $this->belongsTo(User::class);
    }
}

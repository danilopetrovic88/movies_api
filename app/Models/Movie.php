<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'genre',
        'images',
        'likes',
        'user_id',
    ];

    public CONST GENRES = [
        'all',
        'drama',
        'comedy',
        'action',
        'adventure',
        'crime',
        'history',
        'ScyFi',
    ];

    public function user() 
    {
        return $this->belongsTo(User::class);
    }

    public function images() 
    {
        return $this->hasMany(Image::class);
    }

    public function comments() 
    {
        return $this->hasMany(Comment::class);
    }

    public function likes() 
    {
        return $this->hasMany(Like::class);
    }

    public function isLiked() 
    {
        return $this->likes()->where('like', Like::LIKE_STATES[Like::LIKED]);
    }
    public function isDisliked() 
    {
        return $this->likes()->where('like', Like::LIKE_STATES[Like::DISLIKED]);
    }

    public function isUserLiked(User $user) 
    {
        $isMovieLikedByUser = $this->isLiked()->where('user_id', $user->id)->get();
        $isMovieDislikedByUser = $this->isDisliked()->where('user_id', $user->id)->get();
        
        if($isMovieLikedByUser->count() > 0) {
            return $isMovieLikedByUser->first()->like;
        } 
        if($isMovieDislikedByUser->count() > 0) {
            return $isMovieDislikedByUser->first()->like;
        } 

        return Like::LIKE_STATES[Like::NONE];
    }

    public function scopeSearchByTitle($query, $title = "")
    {
        if ($title) {
            $query->where('title', 'like', "%$title%");
        }
        return $query;
    }

    public function scopeFilterByGenre($query, $genre = "")
    {
        if ($genre) {
            $query->where('genre', 'like', "%$genre%");
        }
        return $query;
    }
}

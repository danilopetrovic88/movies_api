<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    use HasFactory;

    protected $fillable = [
        'url',
        'movie_id'
    ];
    

    public function movie() {
        return $this->belongsTo(Movie::class);
    }
}

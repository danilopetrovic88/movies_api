<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateCommentRequest;
use App\Http\Resources\CommentResource;
use App\Models\Comment;
use App\Models\Movie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function store(CreateCommentRequest $request, Movie $movie)
    {

        $data = $request->validated();

        $comment = new Comment($data);

        $comment->movie()->associate($movie);

        $comment->user()->associate(Auth::user());

        $comment->save();

        return new CommentResource($comment->load('user', 'movie'));
    }

    public function destroy(Movie $movie, Comment $comment)
    {
        $comment->movie()->associate($movie)->delete();

        return new CommentResource(null, 204);
    }
}

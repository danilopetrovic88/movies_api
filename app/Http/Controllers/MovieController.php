<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateLikeRequest;
use App\Http\Requests\CreateMovieRequest;
use App\Http\Requests\UpdateMovieRequest;
use App\Http\Resources\GenreResource;
use App\Http\Resources\MovieResource;
use App\Models\Image;
use App\Models\Movie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MovieController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $title = $request->query('title');
        $genre = $request->query(('genre'));
        $movies = Movie::with(['user', 'images', 'likes'])
                ->searchByTitle($title)
                ->filterByGenre($genre);

        return MovieResource::collection($movies->get());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateMovieRequest $request)
    {
        $data = $request->validated();
        unset($data['images']);
        $movie = new Movie($data);
        $movie->user()->associate(Auth::user());
        $movie->save();

        $imgs = [];
        foreach ($request->images as $img) {
            $imgs[] = new Image($img);
        }

        $movie->images()->saveMany($imgs);

        return new MovieResource($movie->load('images', 'user'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Movie $movie)
    {
        $movie->load('user', 'images', 'comments', 'likes');

        return new MovieResource($movie);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateMovieRequest $request, Movie $movie)
    {
        $payload = $request->validated();
        
        if(isset($payload['images'])) {
            $images = $payload['images'];
            $movie->images()->updateOrCreate(['movie_id' => $movie->id],$images);
            unset($payload['images']);
        }
        $movie->update($payload);

        $movie->save();     

        return $this->show($movie);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Movie $movie)
    {
        $movie->delete();

        return new MovieResource(null, 204);
    }

    public function movieLikeStatus(CreateLikeRequest $request, Movie $movie) 
    {
        $payload = $request->validated();
        $movie->likes()->updateOrCreate(['movie_id' => $movie->id, 'user_id' => Auth::user()->id], $payload);
        $movie->save();

        return new MovieResource($movie->load('likes', 'user', 'images'));
    }

    public function getConfiguration() 
    {
        $genres = Movie::all();

        return new GenreResource($genres);
    }
}

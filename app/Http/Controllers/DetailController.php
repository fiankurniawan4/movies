<?php

namespace App\Http\Controllers;

use App\Favorite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DetailController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index($id) {
        $client = new \GuzzleHttp\Client();
        $uri = 'https://www.omdbapi.com/?i=' . urlencode($id) . '&apiKey=77d350cf&page=';

        $res = $client->get($uri);
        $data = json_decode($res->getBody()->getContents(), true);

        $isFavorite = Favorite::where('movies_id', $id)->where('user_id', Auth::user()->id)->exists();
        return view('detail', ['data' => $data, 'isFavorite' => $isFavorite]);
    }

    public function store($id) {
        Favorite::create([
            'movies_id' => $id,
            'user_id' => Auth::user()->id,
        ]);

        return redirect()->route('home.detail', $id)->with('success', 'Added Movies To Favorite');
    }
}

<?php

namespace App\Http\Controllers;

use App\Favorite;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
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

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        $client = new Client();
        $page = $request->input('page', 1);
        $searchMovies = $request->input('search', '');
        $type = $request->input('type', '');
        $uri = 'https://www.omdbapi.com/?s=' . urlencode($searchMovies) . '&type=' . urlencode($type) . '&apiKey=77d350cf&page=' . $page;

        $res = $client->get($uri);
        $data = json_decode($res->getBody()->getContents(), true);

        //search movies
        $movies = isset($data['Search']) ? $data['Search'] : [];
        $totalResults = isset($data['totalResults']) ? $data['totalResults'] : 0;

        //membataskan 10 perpage
        // intinya fungsi untuk pagination jika paginationnya 1 maka 1 2 3 dan ke 3 akan 3 4 5 seterusnya jika ada hasilnya
        $resultsPerPage = 10;
        $totalPages = ceil($totalResults / $resultsPerPage);
        $startPage = max(1, $page - 2);
        $endPage = min($totalPages, $page + 2);

        $user = Auth::user();
        $favorites = $user->favorites->pluck('movies_id')->toArray();
        if (empty($movies)) {
            $movies = [];
            foreach ($favorites as $favoriteMovieId) {
                // mencari movie dari id favorite (movies_id)
                $movieUri = 'https://www.omdbapi.com/?i=' . urlencode($favoriteMovieId) . '&apiKey=77d350cf';
                $movieRes = $client->get($movieUri);
                $movieData = json_decode($movieRes->getBody()->getContents(), true);

                if (isset($movieData['Title'])) {
                    $movies[] = $movieData;
                }
            }
            $showingFavorites = true; // untuk memberitahu jika kita menampilkan favorite movies
        } else {
            // Ini jika movies ada yang sama / ketemu maka akan memprioritaskan favorite movies
            $favoriteMovies = [];
            $nonFavoriteMovies = [];

            foreach ($movies as $movie) {
                if (in_array($movie['imdbID'], $favorites)) {
                    $favoriteMovies[] = $movie;
                } else {
                    $nonFavoriteMovies[] = $movie;
                }
            }

            // Menggabungkan favorite movies dan tidak (ini akan membuat array favorite movies di awal)
            $movies = array_merge($favoriteMovies, $nonFavoriteMovies);
            $showingFavorites = false;
        }

        return view('home', [
            'data' => $movies,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'startPage' => $startPage,
            'endPage' => $endPage,
            'search' => $searchMovies,
            'showingFavorites' => $showingFavorites,
        ]);
    }

}

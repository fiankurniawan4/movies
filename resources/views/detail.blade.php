@extends('layouts.app')
@section('title', 'Detail ')

@section('content')
    <div class="container">
        <div class="flex flex-row p-4">
            <img src="{{ $data['Poster'] }}" alt="Poster" class="rounded-lg">
            <div class="flex flex-col pl-4">
                <h1 class="text-2xl font-bold text-gray-600">{{ $data['Title'] }}</h1>
                <h1 class="text-xl font-semibold text-gray-400">Released: {{ $data['Released'] }}</h1>
                <h1 class="text-xl font-semibold text-gray-400">Genre: {{ $data['Genre'] }}</h1>
                <h1 class="text-xl font-semibold text-gray-400">Type: {{ $data['Type'] }}</h1>
                <h1 class="text-xl font-semibold text-gray-400">Rating: {{ $data['imdbRating'] }}</h1>
                <h1 class="text-xl font-semibold text-gray-400">Writer: {{ $data['Writer'] }}</h1>
                <h1 class="text-xl font-normal text-gray-600">Plot: {{ $data['Plot'] }}</h1>
                @if ($isFavorite)
                    <button type="button" class="px-4 py-2 rounded-md bg-red-400 text-white mt-4">Already Added To
                        Favorite Movies</button>
                @else
                    <form action="{{ route('home.store', $data['imdbID']) }}" method="POST">
                        @csrf
                        <button type="submit" class="px-4 py-2 rounded-md bg-red-400 text-white mt-4">Add To Favorite
                            Movies</button>
                    </form>
                @endif
            </div>
        </div>
    </div>
@endsection

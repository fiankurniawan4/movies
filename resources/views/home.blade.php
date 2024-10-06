@extends('layouts.app')
@section('title', 'Home')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="flex justify-between mb-6">
            <h2 class="text-2xl font-bold mb-6">
                @if($showingFavorites)
                    No results found, Showing your favorite movies
                @else
                    Movie Results
                @endif
            </h2>
            <form class="flex items-center">
                <input type="search" name="search" placeholder="Search movies..." class="rounded-md border border-gray-300 px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200 ease-in-out">
                <select name="type" class="ml-4 rounded-md border border-gray-300 px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200 ease-in-out">
                    <option value="">All Types</option>
                    <option value="movie" {{ request('type') == 'movie' ? 'selected' : '' }}>Movie</option>
                    <option value="series" {{ request('type') == 'series' ? 'selected' : '' }}>Series</option>
                    <option value="episode" {{ request('type') == 'episode' ? 'selected' : '' }}>Episode</option>
                </select>
                <button type="submit" class="ml-2 bg-blue-500 text-white rounded-md px-4 py-2">Search</button>
            </form>
        </div>

        @if (count($data) > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @foreach ($data as $item)
                    <div class="bg-white rounded-lg shadow-md overflow-hidden"> <!-- Movie Poster -->
                        @if (isset($item['Poster']) && $item['Poster'] != 'N/A')
                            <img src="{{ $item['Poster'] }}" alt="{{ $item['Title'] }}" class="w-full h-64 object-cover" loading="lazy">
                        @else
                            <img src="default-image.jpg" alt="No image available" class="w-full h-64 object-cover" loading="lazy">
                        @endif
                        <div class="p-4">
                            <a class="hover:underline" href="{{ route('home.detail', $item['imdbID']) }}">
                                <h3 class="text-xl font-semibold mb-2">{{ $item['Title'] }}</h3>
                            </a>
                            <p class="text-gray-600">Year: {{ $item['Year'] }}</p>
                            <p class="text-gray-600">IMDB ID: {{ $item['imdbID'] }}</p>
                            <p class="text-gray-600">Type: {{ $item['Type'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="mt-8 flex justify-center">
                <nav>
                    <ul class="inline-flex space-x-2">
                        <li class="{{ $currentPage == 1 ? 'opacity-50 pointer-events-none' : '' }}">
                            <a href="{{ url('/home?page=' . ($currentPage - 1)) }}" class="px-4 py-2 border border-gray-300 rounded-md">Previous</a>
                        </li>
                        @for ($i = $startPage; $i <= $endPage; $i++)
                            <li class="{{ $currentPage == $i ? 'bg-blue-500 text-white' : 'bg-gray-200' }}">
                                <a href="{{ url('/home?page=' . $i) }}" class="px-4 py-2 border border-gray-300 rounded-md">{{ $i }}</a>
                            </li>
                        @endfor
                        <li class="{{ $currentPage == $totalPages ? 'opacity-50 pointer-events-none' : '' }}">
                            <a href="{{ url('/home?page=' . ($currentPage + 1)) }}" class="px-4 py-2 border border-gray-300 rounded-md">Next</a>
                        </li>
                    </ul>
                </nav>
            </div>
        @else
            <p class="text-gray-500">No results found.</p>
        @endif
    </div>
@endsection

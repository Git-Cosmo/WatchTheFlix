<?php

namespace App\Http\Controllers;

use App\Models\Media;
use Illuminate\Support\Facades\Auth;

class WatchlistController extends Controller
{
    public function index()
    {
        $watchlist = Auth::user()->watchlist()->paginate(24);

        return view('watchlist.index', compact('watchlist'));
    }

    public function add(Media $media)
    {
        Auth::user()->watchlist()->syncWithoutDetaching($media->id);

        return back()->with('success', 'Added to watchlist');
    }

    public function remove(Media $media)
    {
        Auth::user()->watchlist()->detach($media->id);

        return back()->with('success', 'Removed from watchlist');
    }
}

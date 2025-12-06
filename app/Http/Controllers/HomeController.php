<?php

namespace App\Http\Controllers;

use App\Models\Media;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $featured = Media::published()
            ->latest()
            ->take(6)
            ->get();

        $trending = Media::published()
            ->withCount('ratings')
            ->orderBy('ratings_count', 'desc')
            ->take(12)
            ->get();

        return view('home', compact('featured', 'trending'));
    }
}

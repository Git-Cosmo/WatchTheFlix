<?php

namespace App\Http\Controllers;

use App\Models\TvChannel;
use App\Models\TvProgram;
use Illuminate\Http\Request;

class TvGuideController extends Controller
{
    /**
     * Supported TV Guide countries
     */
    private const SUPPORTED_COUNTRIES = ['UK', 'US'];

    /**
     * Display TV Guide index with country selection
     */
    public function index()
    {
        return view('tv-guide.index');
    }

    /**
     * Display TV channels for a specific country
     */
    public function channels(string $country)
    {
        $country = strtoupper($country);
        
        if (!in_array($country, self::SUPPORTED_COUNTRIES)) {
            abort(404);
        }

        $channels = TvChannel::active()
            ->byCountry($country)
            ->orderBy('channel_number')
            ->get();

        return view('tv-guide.channels', compact('channels', 'country'));
    }

    /**
     * Display programs for a specific channel
     */
    public function channel(TvChannel $channel)
    {
        // Get today's programs
        $todayStart = now()->startOfDay();
        $todayEnd = now()->endOfDay();

        $currentProgram = $channel->currentPrograms()->first();
        $upcomingPrograms = $channel->upcomingPrograms()
            ->where('start_time', '<=', $todayEnd)
            ->take(10)
            ->get();

        return view('tv-guide.channel', compact('channel', 'currentProgram', 'upcomingPrograms'));
    }

    /**
     * Search TV programs
     */
    public function search(Request $request)
    {
        $query = $request->input('query');
        $country = $request->input('country');

        if (empty($query)) {
            return back()->with('error', 'Please enter a search term');
        }

        $programsQuery = TvProgram::where('title', 'like', "%{$query}%")
            ->orWhere('description', 'like', "%{$query}%")
            ->with('channel');

        if ($country) {
            $programsQuery->whereHas('channel', function ($q) use ($country) {
                $q->where('country', strtoupper($country));
            });
        }

        $programs = $programsQuery->orderBy('start_time', 'desc')
            ->paginate(20);

        return view('tv-guide.search', compact('programs', 'query', 'country'));
    }
}

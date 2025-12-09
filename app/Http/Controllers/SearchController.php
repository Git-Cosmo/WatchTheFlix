<?php

namespace App\Http\Controllers;

use App\Models\ForumThread;
use App\Models\Media;
use App\Models\TvChannel;
use App\Models\TvProgram;
use App\Models\User;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    /**
     * Perform a site-wide search across all searchable models
     */
    public function index(Request $request)
    {
        $query = $request->input('q', '');
        $type = $request->input('type', 'all');

        if (empty($query)) {
            return redirect()->back()->with('error', 'Please enter a search term.');
        }

        $results = [];
        $totalResults = 0;

        // Search Media (Movies & TV Shows)
        if ($type === 'all' || $type === 'media') {
            $mediaResults = Media::search($query)
                ->where('is_published', true)
                ->paginate(10, 'mediaPage');
            $results['media'] = $mediaResults;
            $totalResults += $mediaResults->total();
        }

        // Search TV Channels
        if ($type === 'all' || $type === 'channels') {
            $channelResults = TvChannel::search($query)
                ->where('is_active', true)
                ->paginate(10, 'channelsPage');
            $results['channels'] = $channelResults;
            $totalResults += $channelResults->total();
        }

        // Search TV Programs
        if ($type === 'all' || $type === 'programs') {
            $programResults = TvProgram::search($query)
                ->paginate(10, 'programsPage');
            $results['programs'] = $programResults;
            $totalResults += $programResults->total();
        }

        // Search Forum Threads
        if ($type === 'all' || $type === 'forum') {
            $forumResults = ForumThread::search($query)
                ->paginate(10, 'forumPage');
            $results['forum'] = $forumResults;
            $totalResults += $forumResults->total();
        }

        // Search Users (admin only)
        if (auth()->check() && auth()->user()->hasRole('admin')) {
            if ($type === 'all' || $type === 'users') {
                $userResults = User::search($query)
                    ->paginate(10, 'usersPage');
                $results['users'] = $userResults;
                $totalResults += $userResults->total();
            }
        }

        return view('search.results', compact('query', 'type', 'results', 'totalResults'));
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\ForumCategory;
use App\Models\ForumThread;
use App\Models\ForumPost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ForumController extends Controller
{
    public function index()
    {
        $categories = ForumCategory::active()
            ->ordered()
            ->withCount('threads')
            ->with('latestThread.user')
            ->get();

        return view('forum.index', compact('categories'));
    }

    public function category(ForumCategory $category)
    {
        $threads = $category->threads()
            ->with(['user', 'latestPost.user'])
            ->withCount('posts')
            ->orderBy('is_pinned', 'desc')
            ->latest()
            ->paginate(20);

        return view('forum.category', compact('category', 'threads'));
    }

    public function createThread(ForumCategory $category)
    {
        return view('forum.create-thread', compact('category'));
    }

    public function storeThread(Request $request, ForumCategory $category)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'body' => ['required', 'string', 'min:10'],
        ]);

        $thread = $category->threads()->create([
            'user_id' => Auth::id(),
            'title' => $validated['title'],
            'body' => $validated['body'],
        ]);

        // Auto-subscribe creator to thread
        $thread->subscribers()->attach(Auth::id());

        activity()
            ->causedBy(Auth::user())
            ->performedOn($thread)
            ->log('Forum thread created');

        return redirect()->route('forum.thread', $thread)
            ->with('success', 'Thread created successfully');
    }

    public function showThread(ForumThread $thread)
    {
        $thread->incrementViews();
        
        $thread->load(['category', 'user', 'posts.user']);
        
        $isSubscribed = $thread->isSubscribedBy(Auth::user());

        return view('forum.thread', compact('thread', 'isSubscribed'));
    }

    public function replyToThread(Request $request, ForumThread $thread)
    {
        if ($thread->is_locked && !Auth::user()->isAdmin()) {
            return back()->with('error', 'This thread is locked');
        }

        $validated = $request->validate([
            'body' => ['required', 'string', 'min:10'],
        ]);

        $post = $thread->posts()->create([
            'user_id' => Auth::id(),
            'body' => $validated['body'],
        ]);

        return back()->with('success', 'Reply posted successfully');
    }

    public function subscribe(ForumThread $thread)
    {
        if (!$thread->isSubscribedBy(Auth::user())) {
            $thread->subscribers()->attach(Auth::id());
            $message = 'Subscribed to thread';
        } else {
            $thread->subscribers()->detach(Auth::id());
            $message = 'Unsubscribed from thread';
        }

        return back()->with('success', $message);
    }

    public function togglePin(ForumThread $thread)
    {
        $thread->update(['is_pinned' => !$thread->is_pinned]);

        return back()->with('success', $thread->is_pinned ? 'Thread pinned' : 'Thread unpinned');
    }

    public function toggleLock(ForumThread $thread)
    {
        $thread->update(['is_locked' => !$thread->is_locked]);

        return back()->with('success', $thread->is_locked ? 'Thread locked' : 'Thread unlocked');
    }

    public function destroy(ForumThread $thread)
    {
        if (Auth::id() !== $thread->user_id && !Auth::user()->isAdmin()) {
            return back()->with('error', 'Unauthorized');
        }

        $thread->delete();

        return redirect()->route('forum.category', $thread->category)
            ->with('success', 'Thread deleted successfully');
    }
}

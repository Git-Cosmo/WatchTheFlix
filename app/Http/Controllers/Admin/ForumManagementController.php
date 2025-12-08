<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ForumCategory;
use Illuminate\Http\Request;

class ForumManagementController extends Controller
{
    public function index()
    {
        $categories = ForumCategory::withCount('threads')->orderBy('order')->get();

        return view('admin.forum.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.forum.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'order' => ['required', 'integer', 'min:0'],
            'is_active' => ['boolean'],
        ]);

        $category = ForumCategory::create($validated);

        activity()
            ->causedBy(auth()->user())
            ->performedOn($category)
            ->log('Forum category created');

        return redirect()->route('admin.forum.admin.index')
            ->with('success', 'Forum category created successfully');
    }

    public function edit(ForumCategory $category)
    {
        return view('admin.forum.edit', compact('category'));
    }

    public function update(Request $request, ForumCategory $category)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'order' => ['required', 'integer', 'min:0'],
            'is_active' => ['boolean'],
        ]);

        $category->update($validated);

        activity()
            ->causedBy(auth()->user())
            ->performedOn($category)
            ->log('Forum category updated');

        return back()->with('success', 'Forum category updated successfully');
    }

    public function destroy(ForumCategory $category)
    {
        activity()
            ->causedBy(auth()->user())
            ->performedOn($category)
            ->log('Forum category deleted');

        $category->delete();

        return redirect()->route('admin.forum.admin.index')
            ->with('success', 'Forum category deleted successfully');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Author;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Gate;

class AuthorController extends Controller
{

    public function index(Request $request)
    {
        $status = $request->input('status');

        $query = Author::query()
            ->withCount('posts')
            ->latest();

        if ($status === 'active') {
            $query->active();
        } elseif ($status === 'inactive') {
            $query->where('is_active', false);
        }

        $authors = $query->paginate(10)
            ->withQueryString();

        return Inertia::render('Authors/Index', [
            'authors' => $authors,
            'filters' => [
                'status' => $status,
            ],
        ]);
    }


    public function create()
    {
        return Inertia::render('Authors/Form');
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|unique:authors,email',
            'bio' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        Author::create($validated);

        return redirect()->route('authors.index')
            ->with('message', 'Author created successfully.');
    }


    public function show(Author $author)
    {
        return Inertia::render('Authors/Show', [
            'author' => $author->load(['posts' => function ($query) {
                $query->latest()->limit(5);
            }]),
            'totalPosts' => $author->posts()->count(),
            'publishedPosts' => $author->posts()->published()->count(),
        ]);
    }


    public function edit(Author $author)
    {
        return Inertia::render('Authors/Form', [
            'author' => $author,
        ]);
    }


    public function update(Request $request, Author $author)
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|unique:authors,email,' . $author->id,
            'bio' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $author->update($validated);

        return redirect()->route('authors.index')
            ->with('message', 'Author updated successfully.');
    }


    public function destroy(Author $author)
    {

        if ($author->posts()->count() > 0) {
            return back()->with('error', 'Cannot delete author with associated posts.');
        }

        $author->delete();

        return redirect()->route('authors.index')
            ->with('message', 'Author deleted successfully.');
    }
}

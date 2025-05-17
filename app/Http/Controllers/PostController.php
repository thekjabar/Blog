<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Author;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PostController extends Controller
{

    public function index(Request $request)
    {
        $status = $request->input('status');

        $query = Post::query()
            ->with('author:id,name,email')
            ->latest();

        if ($status === 'published') {
            $query->published();
        } elseif ($status === 'unpublished') {
            $query->unpublished();
        }

        $posts = $query->paginate(10)
            ->withQueryString();

        return Inertia::render('Posts/Index', [
            'posts' => $posts,
            'filters' => [
                'status' => $status,
            ],
        ]);
    }


    public function create()
    {
        return Inertia::render('Posts/Form', [
            'authors' => Author::select('id', 'name')->active()->get(),
        ]);
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|max:255',
            'content' => 'required',
            'author_id' => 'required|exists:authors,id',
            'is_published' => 'boolean',
        ]);

        Post::create($validated);

        return redirect()->route('posts.index')
            ->with('message', 'Post created successfully.');
    }


    public function show(Post $post)
    {
        return Inertia::render('Posts/Show', [
            'post' => $post->load('author:id,name,email'),
        ]);
    }


    public function edit(Post $post)
    {
        return Inertia::render('Posts/Form', [
            'post' => $post->only(['id', 'title', 'content', 'author_id', 'is_published']),
            'authors' => Author::select('id', 'name')->active()->get(),
        ]);
    }


    public function update(Request $request, Post $post)
    {
        $validated = $request->validate([
            'title' => 'required|max:255',
            'content' => 'required',
            'author_id' => 'required|exists:authors,id',
            'is_published' => 'boolean',
        ]);

        $post->update($validated);

        return redirect()->route('posts.index')
            ->with('message', 'Post updated successfully.');
    }


    public function destroy(Post $post)
    {
        $post->delete();

        return redirect()->route('posts.index')
            ->with('message', 'Post deleted successfully.');
    }
}

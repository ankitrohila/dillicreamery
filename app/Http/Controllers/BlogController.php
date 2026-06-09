<?php
namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\BlogCategory;

class BlogController extends Controller
{
    public function index()
    {
        $blogs = Blog::published()->with(['author', 'category'])->latest('published_at')->paginate(9);
        $categories = BlogCategory::withCount(['blogs' => fn($q) => $q->published()])->get();
        return view('blog.index', compact('blogs', 'categories'));
    }

    public function show(string $slug)
    {
        $blog = Blog::published()->where('slug', $slug)->with(['author', 'category'])->firstOrFail();
        $related = Blog::published()->where('category_id', $blog->category_id)->where('id', '!=', $blog->id)->take(3)->get();
        return view('blog.show', compact('blog', 'related'));
    }
}

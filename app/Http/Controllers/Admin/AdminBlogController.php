<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminBlogController extends Controller {
    public function index() { return view('admin.blog.index', ['posts'=>Blog::latest()->paginate(20)]); }
    public function create() { return view('admin.blog.create'); }
    public function store(Request $r) {
        $r->validate(['title'=>'required','content'=>'required','excerpt'=>'nullable']);
        Blog::create(['title'=>$r->title,'slug'=>Str::slug($r->title),'content'=>$r->content,'excerpt'=>$r->excerpt,'author_id'=>auth()->id(),'is_published'=>$r->boolean('is_published'),'published_at'=>$r->boolean('is_published')?now():null]);
        return redirect()->route('admin.blog.index')->with('success','Post created!');
    }
    public function edit(Blog $blog) { return view('admin.blog.edit', compact('blog')); }
    public function update(Request $r, Blog $blog) {
        $r->validate(['title'=>'required','content'=>'required']);
        $blog->update(['title'=>$r->title,'content'=>$r->content,'excerpt'=>$r->excerpt,'is_published'=>$r->boolean('is_published'),'published_at'=>$r->boolean('is_published')?($blog->published_at??now()):null]);
        return back()->with('success','Post updated!');
    }
    public function destroy(Blog $blog) { $blog->delete(); return back()->with('success','Post deleted.'); }
}

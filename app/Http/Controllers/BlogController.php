<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;

use App\Models\Blog;
use App\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Pagination\Paginator;
use DB;
use Alert;

class BlogController extends Controller
{

    /**
     * create
     *
     * @return void
     */
    public function create()
    {
        return view('blog.create');
    }

    public function edit(Blog $blog)
    {
        return view('blog.edit', compact('blog'));
    }


    public function index()
    {

        $user_id  = Auth::id();
        // $posts = DB::select('select * from post where status = ?', array('publish'));
        // $blogs = DB::table('blogs')->where('user_id', $user_id);
        $blogs = DB::select("select * from blogs where user_id = $user_id");
        $blogsAlternate = Blog::latest();
        Paginator::useBootstrap();
        return view('blog.manage', compact('blogsAlternate'), compact('blogs'));
    }

    /**
     * store
     *
     * @param  mixed $request
     * @return void
     */
    public function store(Request $request)
    {
        // Alert::success('pesan yang ingin disampaikan', 'Judul Pesan');
        $this->validate($request, [
            'image'     => 'required|image|mimes:png,jpg,jpeg',
            'title'     => 'required',
            'content'   => 'required',
            'category' => 'required_without_all',
        ]);

        //upload image
        $image = $request->file('image');
        $cat = $request->input('category');
        $image->storeAs('public/blogs', $image->hashName());
        $user_id  = Auth::id();

        $cats = $request->input('category');
        $catArray = array();

        foreach ($cats as $cat) {
            $catArray[] = $cat;
        }



        $blog = Blog::create([
            'image'     => $image->hashName(),
            'title'     => $request->title,
            'category'     => json_encode($catArray),
            'user_id'     => $user_id,
            'content'   => $request->content

        ]);

        if ($blog) {
            //redirect dengan pesan sukses
            return redirect('blog/manage')->with(['success' => 'Blog Berhasil Disimpan!']);
        } else {
            //redirect dengan pesan error
            return redirect('blog/manage')->with(['error' => 'Blog Gagal Disimpan!']);
        }
    }

    public function destroy($id)
    {
        $blog = Blog::findOrFail($id);
        Storage::disk('local')->delete('public/blogs/' . $blog->image);
        $blog->delete();

        if ($blog) {
            //redirect dengan pesan sukses
            return redirect('blog/manage')->with(['success' => 'Blog Berhasil Dihapus!']);
        } else {
            //redirect dengan pesan error
            return redirect('blog/manage')->with(['error' => 'Blog Gagal Dihapus!']);
        }
    }

    /**
     * update
     *
     * @param  mixed $request
     * @param  mixed $blog
     * @return void
     */
    public function update(Request $request, Blog $blog)
    {
        $this->validate($request, [
            'title'     => 'required',
            'content'   => 'required'
        ]);

        $blog = Blog::findOrFail($blog->id);
        $user_id  = Auth::id();

        if ($request->file('image') == "") {
            $blog->update([
                'title'     => $request->title,
                'category'     => $request->input('category'),
                'user_id'     => $user_id,
                'content'   => $request->content
            ]);
        } else if ($request->file('video') == "" && $request->file('image') != "") {
            //hapus old image
            Storage::disk('local')->delete('public/blogs/' . $blog->image);
            //upload new image
            $image = $request->file('image');
            $image->storeAs('public/blogs', $image->hashName());
            $blog->update([
                'image'     => $image->hashName(),
                'title'     => $request->title,
                'category'     => $request->input('category'),
                'user_id'     => $user_id,
                'content'   => $request->content
            ]);
        }

        if ($blog) {
            //redirect dengan pesan sukses
            return redirect('blog/manage')->with(['success' => 'Data Berhasil Diupdate!']);
        } else {
            //redirect dengan pesan error
            return redirect('blog/manage')->with(['error' => 'Data Gagal Diupdate!']);
        }
    }

    // Add Blog
    public function show(Blog $blog)
    {
        $blog_info = DB::select("select * from view_blog where id=$blog->id");
        return view('read_blog')->with(compact('blog','blog_info'));
    }




    // Add Blog
    public function add()
    {
        return view('blog.create');
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Models\Portfolio;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use DB;
use Alert;

class PortfolioController extends Controller
{

    public function manage()
    {
        $user_id  = Auth::id();
        $portfolio = DB::select("select * from view_project where user_id = $user_id");
        return view('portfolio.manage')->with(compact('portfolio'));
    }

    public function edit(Portfolio $portfolio){
        $portfolio = DB::select("select * from view_project where id = $portfolio->id");
        return view('portfolio.edit')->with(compact('portfolio'));
    }

    public function show(Portfolio $portfolio){
        $portfolio = DB::select("select * from view_project where id = $portfolio->id");
        return view('read_project')->with(compact('portfolio'));
    }
    public function create(){
        return view('portfolio.create');
    }


     /**
     * update
     *
     * @param  mixed $request
     * @param  mixed $blog
     * @return void
     */
    public function update(Request $request, Portfolio $portfolio)
    {
        $this->validate($request, [
            'title'     => 'required',
            'content'   => 'required',
            'category' => 'required_without_all',
        ]);

        $portfolio = Portfolio::findOrFail($portfolio->id);
        $user_id  = Auth::id();

        if ($request->file('image') == "") {
            $portfolio->update([
                'title'     => $request->title,
                'title'     => $request->title,
                'link'     => $request->link,
                'category'     => $request->input('category'),
                'user_id'     => $user_id,
                'content'   => $request->content
            ]);
        } else if ($request->file('image') != "") {
            //hapus old image
            Storage::disk('local')->delete('public/portfolio/' . $portfolio->image);
            //upload new image
            $image = $request->file('image');
            $image->storeAs('public/portfolio', $image->hashName());
            $portfolio->update([
                'image'     => $image->hashName(),
                'title'     => $request->title,
                'link'     => $request->link,
                'category'     => $request->input('category'),
                'user_id'     => $user_id,
                'content'   => $request->content
            ]);
        }

        if ($portfolio) {
            //redirect dengan pesan sukses
            return redirect('portfolio/manage')->with(['success' => 'Data Berhasil Diupdate!']);
        } else {
            //redirect dengan pesan error
            return redirect('portfolio/manage')->with(['error' => 'Data Gagal Diupdate!']);
        }
    }

    public function destroy($id)
    {
        $portfolio = Portfolio::findOrFail($id);
        Storage::disk('local')->delete('public/portfolio/' . $portfolio->image);
        $portfolio->delete();

        if ($portfolio) {
            //redirect dengan pesan sukses
            return redirect('portfolio/manage')->with(['success' => 'Portfolio Berhasil Dihapus!']);
        } else {
            //redirect dengan pesan error
            return redirect('portfolio/manage')->with(['error' => 'Portfolio Gagal Dihapus!']);
        }
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
            'link'   => 'required',
            'category' => 'required_without_all',
        ]);

        //upload image
        $image = $request->file('image');
        $cat = $request->input('category');
        $link = $request->input('link');
        $image->storeAs('public/portfolio', $image->hashName());
        $user_id  = Auth::id();

        $cats = $request->input('category');
        $catArray = array();

        foreach ($cats as $cat) {
            $catArray[] = $cat;
        }

        $portfolio = Portfolio::create([
            'image'     => $image->hashName(),
            'title'     => $request->title,
            'category'     => json_encode($catArray),
            'user_id'     => $user_id,
            'link'     => $request->link,
            'content'   => $request->content

        ]);

        if ($portfolio) {
            //redirect dengan pesan sukses
            return redirect('portfolio/manage')->with(['success' => 'Portfolio Berhasil Disimpan!']);
        } else {
            //redirect dengan pesan error
            return redirect('portfolio/manage')->with(['error' => 'Portfolio Gagal Disimpan!']);
        }
    }

}

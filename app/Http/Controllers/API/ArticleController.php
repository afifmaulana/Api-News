<?php

namespace App\Http\Controllers\API;

use App\Article;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ArticleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function store(Request $request)
    {
        $rules = Validator::make($request->all(),[
            'title' => 'required',
            'content_article' => 'required',
            'image' => 'file|required|mimes:jpg,jpeg,png|max:2048',
        ]);
        if ($rules->fails()) {
            return response()->json([
                'message' => $rules->errors(),
                'status' => false,
                'data' => (object)[]
            ]);
        }

        $image_article = $request->file('image');
        $path = time(). $image_article->getClientOriginalExtension();
        $destinationPath = public_path('uploads/articles');
        $image_article->move($destinationPath, $path);

        $article = new Article();
        $article->user_id = Auth::user()->id;
        $article->title = $request->title;
        $article->category_id = $request->category_id;
        $article->content = $request->content_article;
        $article->image = $path;

        $article->save();

        return response()->json([
            'message' => 'Berhasil Menambahkan Article',
            'status' => true,
            'data' => $article
        ]);
    }

    public function updateArticle(Request $request, $id)
    {
        $article = Article::find($id);
        $article->title = $request->title;
        $article->category_id = $request->category_id;
        $article->content = $request->content_article;

        $article->update();

        return response()->json([
            'message' => 'Berhasil Mengubah Article',
            'status' => true,
            'data' => $article
        ]);
    }

    public function updateImage(Request $request, $id)
    {
//        dd($request->all());
        $image_article = $request->file('image');
        $path = time(). $image_article->getClientOriginalExtension();
        $destinationPath = public_path('uploads/articles');
        $image_article->move($destinationPath, $path);

        $article = Article::find($id);
        $article->image = $path;

        $article->update();

        return response()->json([
            'message' => 'Berhasil Mengubah Article',
            'status' => true,
            'data' => $article
        ]);
    }

    public function delete($id)
    {
        $article = Article::find($id);
        $article->status = false;
        $article->update();

        return response()->json([
            'message' => 'Berhasil Menghapus Artikel',
            'status' => true,
            'data' => $article
        ]);
    }

}
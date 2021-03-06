<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\ReviewResource;
use App\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ReviewController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api')->except('showReviewArticle');
    }

    public function ReviewStore(Request $request)
    {

        $rules = Validator::make($request->all(),[
            'article_id' => 'required',
            'rating' => 'required',
            'comment' => 'required'
        ]);
        if ($rules->fails()) {
            return response()->json([
                'message' => $rules->errors(),
                'status' => false,
                'data' => (object)[]
            ], 400);
        }

        $review = new Review();
        $review->user_id = Auth::user()->id;
        $review->article_id = $request->article_id;
        $review->rating = $request->rating;
        $review->comment = $request->comment;

        $review->save();

        return response()->json([
            'message' => 'Berhasil menambahkan Review',
            'status' => true,
            'data' => (object)[]
        ], 200);
    }

    public function ReviewUpdate(Request $request, $id)
    {
        $user = Auth::user()->id;
        $review = Review::find($id, $user);
        $review->comment = $request->comment;

        $review->update();

        return response()->json([
            'message' => 'Berhasil Mengubah Komentar',
            'status' => true,
            'data' => $review
        ], 200);
    }

    public function showReviewArticle($article_id)
    {
        $reviews = Review::where('article_id', $article_id)->get();
        return response()->json([
            'message' => 'Berhasil Menampilkan Komentar',
            'status' => true,
            'data' => ReviewResource::collection($reviews),
        ]);
    }

    public function destroy($id)
    {
        $review = Review::find($id);
        $review->delete();

        return response()->json([
            'message' => 'Berhasil Menghapus Komentar',
            'status' => true,
            'data' => (object)[]
        ], 200);
    }

}

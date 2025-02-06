<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Restaurant;
use App\Models\Review;
use Illuminate\Support\Facades\Auth; // Authファサードを使用

class ReviewController extends Controller
{
    public function index(Restaurant $restaurant){

         // 現在のユーザーを取得
         $user = Auth::user();

         // 条件分岐でレビューを取得
         if ($user && $user->subscribed('premium_plan')) {
             // 有料会員の場合、ページネーションで5件ずつ取得
             $reviews = Review::where('restaurant_id', $restaurant->id)
                 ->orderBy('created_at', 'desc')
                 ->paginate(5);
         } else {
             // 無料会員の場合、最新の3件のみ取得
             $reviews = Review::where('restaurant_id', $restaurant->id)
                 ->orderBy('created_at', 'desc')
                 ->take(3)
                 ->get();
         }
 
         // ビューにデータを渡す
         return view('restaurants.reviews.index', compact('reviews', 'restaurant'));

 }

 public function create(Restaurant $restaurant)
 {
     return view('reviews.create', compact('restaurant'));
 }

 public function store(Request $request, Restaurant $restaurant)
 {
            // バリデーション
            $validatedData = $request->validate([
                'score' => ['required', 'integer', 'min:1', 'max:5'],
                'content' => ['required'], 
            ]);

                // デバッグ用
    \Log::info('Restaurant ID: ' . $request->input('restaurant_id'));
                // レビューの新規作成
        Review::create([
        'score' => $validatedData['score'],  // バリデーションを通ったスコアを保存
        'content' => $validatedData['content'],  // バリデーションを通ったレビュー内容を保存
        'restaurant_id' => $restaurant->id,  // 直接インスタンスから取得
        'user_id' => auth()->id(),  // 現在ログインしているユーザーのIDを保存
        ]);
    
            // フラッシュメッセージを設定してリダイレクト
            return redirect()
                ->route('restaurants.reviews.index', $restaurant)->with('flash_message', 'レビューを投稿しました。');
        }

        public function edit(Restaurant $restaurant, Review $review)
        {

             // 現在のユーザーを取得
            $user = Auth::user();

            if($user->id !== $review->user_id ){
                return redirect()
                ->route('restaurants.reviews.index', $restaurant)->with('error_message', '不正なアクセスです。');
            }
                return view('reviews.edit', compact('restaurant', 'review'));
        }

        public function update(Request $request, Review $review, Restaurant $restaurant)
        {
              // 現在のユーザーを取得
              $user = Auth::user();

              if($user->id !== $review->user_id ){
                  return redirect()
                  ->route('restaurants.reviews.index', $restaurant)->with('error_message', '不正なアクセスです。');
              }

                           // バリデーション
             $validatedData = $request->validate([
                'score' => ['required', 'integer', 'min:1', 'max:5'],
                'content' => ['required'], 
            ]);

              // データの更新
             $review->update([
                'score' => $validatedData['score'],
                'content' => $validatedData['content'],
             ]);

              // フラッシュメッセージを設定してリダイレクト
            return redirect()
            ->route('restaurants.reviews.index', $restaurant)->with('flash_message', 'レビューを編集しました。');
        }

        public function destroy(Request $request, Review $review, Restaurant $restaurant)
        {
             // 現在のユーザーを取得
             $user = Auth::user();

             if($user->id !== $review->user_id ){
                 return redirect()
                 ->route('restaurants.reviews.index', $restaurant)->with('error_message', '不正なアクセスです。');
             }

             $review->delete();

               // フラッシュメッセージを設定してリダイレクト
            return redirect()
            ->route('restaurants.reviews.index', $restaurant)->with('flash_message', 'レビューを削除しました。');
        }
 }


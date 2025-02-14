<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Restaurant;
use App\Models\Category;

class RestaurantController extends Controller
{
    public function index(Request $request)
    {
        $keyword = $request->input('keyword');
        $category_id = $request->input('category_id');
        $price = $request->input('price');

        $sorts = [
            '掲載日が新しい順' => 'created_at desc',
            '価格が安い順' => 'lowest_price asc',
        ];

        $sort_query = [];
        $sorted = 'created_at desc';

        if ($request->has('select_sort')) {
            $slices = explode(' ', $request->input('select_sort'));
            $sort_query[$slices[0]] = $slices[1];
            $sorted = $request->input('select_sort');
        }

        // クエリを構築
        $query = Restaurant::query();

        // 予約数でソートする場合のみ withCount を追加
        if (isset($sort_query['reservations_count'])) {
            $query->withCount('reservations')->orderByDesc('reservations_count'); // 予約数で降順ソート
        }

       
        if ($keyword !== null) {
            $restaurants = Restaurant::whereHas('categories', function ($query) use ($keyword) {
                $query->where('categories.name', 'like', "%{$keyword}%");
            })
            ->orWhereHas('restaurants', function ($query) use ($keyword) {
                $query->where('restaurants.address', 'like', "%{$keyword}%");
            })
            ->orWhereHas('restaurants', function ($query) use ($keyword) {
                $query->where('restaurants.name', 'like', "%{$keyword}%");
            })
            ->sortable($sort_query)->orderBy('created_at', 'desc')->paginate(15);
            $total = $restaurants->total();
        } elseif ($category_id !== null) {
            $restaurants = Restaurant::whereHas('categories', function ($query) {
                $query->where('categories.id', $category_id);
            })
            ->sortable($sort_query)->orderBy('created_at', 'desc')->paginate(15);
            $total = Restaurant::where('category_id', $request->category)->count();
       } elseif ($price !== null) {
            $restaurants = Restaurant::table('restaurants')->where('lowest_price', '<=', $price)
            ->sortable($sort_query)->orderBy('created_at', 'desc')->paginate(15);
            $total = $restaurants->total();
       } else {
        $restaurants = Restaurant::sortable($sort_query)->orderBy('created_at', 'desc')->paginate(15);
        $total = $restaurants->total();
       }


        $categories = Category::all();

        return view('restaurants.index', compact('keyword', 'category_id', 'price', 'sorts', 'sorted', 'restaurants', 'categories', 'total'));
    }

}

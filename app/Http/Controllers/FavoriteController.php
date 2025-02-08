<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Restaurant;
use Illuminate\Support\Facades\Auth; // Authファサードを使用

class FavoriteController extends Controller
{
    public function index()
    {
        $favorite_restaurants = Auth::user()
            ->favorite_restaurants()
            ->orderBy('restaurant_user.created_at', 'desc')
            ->paginate(15);

        return view('favorites.index', compact('favorite_restaurants'));
    }

    public function store($restaurant_id)
    {
        Auth::user()->favorite_restaurants()->attach($restaurant_id);

        return redirect()->back()->with('flash_message', 'お気に入りに追加しました。');
        
    }

    public function destroy($restaurant_id)
    {
        Auth::user()->favorite_restaurants()->detach($restaurant_id);

        return redirect()->back()->with('flash_message', 'お気に入りを解除しました。');
        
    }
}

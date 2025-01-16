<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Restaurant;
use App\Models\Category;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(){ 

        $highly_rated_restaurants = Restaurant::take(6)->get();
        $categories = Category::all();
        $new_restaurants = Category::orderBy('created_at', 'desc')->take(6)->get();
        return view('user.home.index', compact('highly_rated_restaurants','categories','new_restaurants'));
        }

}

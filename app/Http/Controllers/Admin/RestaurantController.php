<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Restaurant;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Requests\RestaurantRequest;

class RestaurantController extends Controller
{
    //一覧ページ
    public function index(Request $request){
        $keyword = $request->keyword;
        if($keyword !== null){
       
        $restaurants = Restaurant::where('name', 'like', "%{$keyword}%")->paginate(15);
        $total = $restaurants->total();
        }
        else{
            $restaurants = Restaurant::paginate(15);
            $total = $restaurants->total();
        };

        return view('admin.restaurants.index', compact('restaurants','total','keyword'));
}

//詳細ページ
public function show(Restaurant $restaurant){

    return view('admin.restaurants.show', compact('restaurant'));
}

//作成ページ
public function create()
{
    $categories = Category::all();
    return view('admin.restaurants.create', compact('categories'));
}

    // 登録機能
    public function store(RestaurantRequest $request)
    {
       // フォームの入力内容をもとに、テーブルにデータを追加する
       $restaurant = new Restaurant();
       $restaurant->name = $request->input('name');
       $restaurant->description = $request->input('description');
       $restaurant->lowest_price = $request->input('lowest_price');
       $restaurant->highest_price = $request->input('highest_price');
       $restaurant->postal_code = $request->input('postal_code');
       $restaurant->address = $request->input('address');
       $restaurant->opening_time = $request->input('opening_time');
       $restaurant->closing_time = $request->input('closing_time');
       $restaurant->seating_capacity = $request->input('seating_capacity');

        // アップロードされたファイル（name="image"）が存在すれば処理を実行する
       if ($request->hasFile('image')) {
        $image = $request->file('image')->store('public/restaurants');
        $restaurant->image = basename($image);
       }else{
        $restaurant->image = '';
       }

       $restaurant->save(); 

       $category_ids = array_filter($request->input('category_ids'));
       $restaurant->categories()->sync($category_ids);

        return redirect()->route('admin.restaurants.index')->with('flash_message', '店舗を登録しました。');
     
    }

     // 編集ページ
     public function edit(Restaurant $restaurant)
     {
        $categories = Category::all();
        $category_ids = $restaurant->categories->pluck('id')->toArray();
        return view('admin.restaurants.edit',compact('restaurant','categories','category_ids'));
     }    

    // 更新機能
    public function update(RestaurantRequest $request, Restaurant $restaurant)
    {
        // フォームの入力内容をもとに、テーブルにデータを更新する
        $restaurant->name = $request->input('name');
        $restaurant->description = $request->input('description');
        $restaurant->lowest_price = $request->input('lowest_price');
        $restaurant->highest_price = $request->input('highest_price');
        $restaurant->postal_code = $request->input('postal_code');
        $restaurant->address = $request->input('address');
        $restaurant->opening_time = $request->input('opening_time');
        $restaurant->closing_time = $request->input('closing_time');
        $restaurant->seating_capacity = $request->input('seating_capacity');
 
         // アップロードされたファイル（name="image"）が存在すれば処理を実行する
        if ($request->hasFile('image')) {
         $image = $request->file('image')->store('public/restaurants');
         $restaurant->image = basename($image);
        }
        $restaurant->save(); 

        $category_ids = array_filter($request->input('category_ids'));
        $restaurant->categories()->sync($category_ids);

        return redirect()->route('admin.restaurants.show', $restaurant->id)->with('flash_message', '店舗を編集しました。');
    }     

        // 削除機能
        public function destroy(Restaurant $restaurant) {
    
            $restaurant->delete();
    
            return redirect()->route('admin.restaurants.index')->with('flash_message', '店舗を削除しました。');
        
        }
}
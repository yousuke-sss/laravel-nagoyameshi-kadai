<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Requests\CreateCategoryRequest;

class CategoryController extends Controller
{
       //一覧ページ
       public function index(Request $request){
        $keyword = $request->keyword;
        if($keyword !== null){
       
        $categories = Category::where('name', 'like', "%{$keyword}%")->paginate(15);
        $total = $categories->total();
        }
        else{
            $categories = Category::paginate(15);
            $total = $categories->total();
        };

        return view('admin.categories.index', compact('categories','total','keyword'));
}

    // 作成機能
    public function store(CreateCategoryRequest $request)
    {
       // フォームの入力内容をもとに、テーブルにデータを追加する
       $category = new Category();
       $category->name = $request->input('name');
       $category->save(); 

        return redirect()->route('admin.categories.index')->with('flash_message', 'カテゴリを登録しました。');
     
    }

        // 更新機能
        public function update(CreateCategoryRequest $request, Category $category )
        {
           // フォームの入力内容をもとに、テーブルにデータを更新する
           $category->name = $request->input('name');
           $category->save(); 
    
            return redirect()->route('admin.categories.index')->with('flash_message', 'カテゴリを編集しました。');
         
        }

        // 削除機能
        public function destroy(Category $category )
        {
            $category->delete();
            return redirect()->route('admin.categories.index')->with('flash_message', 'カテゴリを削除しました。');
         
        }

}

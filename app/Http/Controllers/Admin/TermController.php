<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Term;
use Illuminate\Http\Request;

class TermController extends Controller
{
    public function index(){ 

        $term = Term::first();
    
        return view('admin.terms.index', compact('term'));
        }

        public function edit(Term $term){
    
            return view('admin.terms.edit', compact('term'));
            }
    
    public function update(Request $request, Term $term){
        // バリデーションを設定する
        $request->validate([
            'content' => 'required'
        ]);

        $term->content = $request->input('content');
        $term->save();

        return redirect()->route('admin.terms.index')->with('flash_message', '利用規約を編集しました。');
    }


}

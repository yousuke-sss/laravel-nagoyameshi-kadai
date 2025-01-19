<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth; // Authファサードを使用
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index(){
        
           // 現在ログイン中のユーザー情報を取得
           $user = Auth::user(); 

        return view('users.index', compact('user'));
    }

    // 編集ページ
    public function edit(User $user)
    {
         // 現在ログイン中のユーザー情報を取得
         $currentUser = Auth::user();

         // ログイン中のユーザーIDと編集対象のユーザーIDが一致しない場合
         if ($user->id !== $currentUser->id) {
             // セッションにフラッシュメッセージを設定
             return redirect()
                 ->route('user.index')->with('error_message', '不正なアクセスです。');
         }

       return view('users.edit',compact('user'));
    }    

    public function update(Request $request, User $user)
    {
        // 現在ログイン中のユーザー情報を取得
        $currentUser = Auth::user();

        // バリデーション
        $validatedData = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'kana' => ['required', 'string', 'max:255', 'regex:/^[ァ-ヶー]+$/u'], // カタカナのみ許可
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)], // 自身のデータは除外
            'postal_code' => ['required', 'digits:7'], // 数値7桁
            'address' => ['required', 'string', 'max:255'],
            'phone_number' => ['required', 'digits_between:10,11'], // 数値10～11桁
            'birthday' => ['nullable', 'digits:8'], // NULL許容、数値8桁
            'occupation' => ['nullable', 'string', 'max:255'], // NULL許容
        ]);

        // データの更新
        $user->update($validatedData);

        // フラッシュメッセージを設定してリダイレクト
        return redirect()
            ->route('user.index')->with('flash_message', '会員情報を編集しました。');
    }

}

<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class SubscriptionController extends Controller
{
    //有料プラン登録ページ
    public function create()
{
    $intent = Auth::user()->createSetupIntent();
    return view('subscription.create', compact('intent'));
}

   public function store(Request $request)
    {
        $request->user()->newSubscription('premium_plan', 'price_1QnFlYBGbzCnnsvRsaBS97pi')->create($request->paymentMethodId);

        return redirect()->route('home')->with('flash_message', '有料プランへの登録が完了しました。');      
     
    }

    // 編集ページ
    public function edit(Request $request)
    {
        $user = Auth::user();
        $intent = Auth::user()->createSetupIntent();
       
       return view('subscription.edit',compact('user','intent'));
    }
    
    public function update(Request $request)
    {
        // ログイン中のユーザーを取得
        $user = Auth::user();

        // 支払い方法を更新
        $user->updateDefaultPaymentMethod($request->paymentMethodId);

        // トップページへリダイレクト
        return redirect()->route('home')->with('flash_message', 'お支払い方法を変更しました。');
    }

    public function cancel()
    {
        return view('subscription.cancel');
    }

    public function destroy(Request $request)
    {

        
        // ログイン中のユーザーを取得
        $user = Auth::user();

        $user->subscription('premium_plan', 'price_1QnFlYBGbzCnnsvRsaBS97pi')->canceled();

        // トップページへリダイレクト
        return redirect()->route('home')->with('flash_message', '有料プランを解約しました。');
    }
    
}

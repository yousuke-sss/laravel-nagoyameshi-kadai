<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Restaurant;
use App\Models\Reservation;
use Illuminate\Support\Facades\Auth; // Authファサードを使用


class ReservationController extends Controller
{
    public function index(Request $request)
    {
        $reservations = Reservation::where('user_id', Auth::id())
            ->orderBy('reserved_datetime', 'desc')
            ->paginate(15);

        return view('reservations.index', compact('reservations'));
    }

    public function create(Restaurant $restaurant)
 {
     return view('reservations.create', compact('restaurant'));
 }

 public function store(Request $request, Restaurant $restaurant)
 {
            // バリデーション
            $validatedData = $request->validate([
               'reservation_date' => ['required', 'date_format:Y-m-d'],
               'reservation_time' => ['required', 'date_format:H:i'],
               'number_of_people' => ['required', 'integer', 'between:1,50'],
            ]);

        Reservation::create([
            'reserved_datetime' => $validatedData['reservation_date'] . ' ' . $validatedData['reservation_time'],
            'number_of_people' => $validatedData['number_of_people'],
            'restaurant_id' => $restaurant->id,
            'user_id' => Auth::id(),
        ]);
    
            // フラッシュメッセージを設定してリダイレクト
            return redirect()
                ->route('reservations.index', $restaurant)->with('flash_message', '予約が完了しました。');
        }

        public function destroy(Reservation $reservation)
        {
             // 現在のユーザーを取得
             $user = Auth::user();

             if($user->id !== $reservation->user_id ){
                 return redirect()
                 ->route('reservations.index')->with('error_message', '不正なアクセスです。');
             }

             $reservation->delete();

               // フラッシュメッセージを設定してリダイレクト
            return redirect()
            ->route('reservations.index')->with('flash_message', '予約をキャンセルしました。');
        }
}

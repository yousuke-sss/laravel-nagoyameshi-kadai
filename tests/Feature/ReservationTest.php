<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Admin;
use App\Models\Restaurant;
use App\Models\Reservation;
use Illuminate\Support\Facades\Auth; // Authファサードを使用
use Illuminate\Support\Facades\Hash;

class ReservationTest extends TestCase
{
    use RefreshDatabase;

    // 予約一覧ページ
    //未ログインのユーザーは会員側の予約一覧ページにアクセスできない
    public function test_guest_cannot_access_reservation_index()
    {
        $response = $this->get(route('reservations.index'));
        $response->assertRedirect(route('login'));
    }

    //ログイン済みの無料会員は会員側の予約一覧ページにアクセスできない
    public function test_regular_user_can_access_reservation_index()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get(route('reservations.index'));
        $response->assertRedirect(route('subscription.create'));
    }

    //ログイン済みの有料会員は会員側の予約一覧ページにアクセスできる
    public function test_paid_user_can_access_reservation_index()
    {
        $user = User::factory()->create();
        $user->newSubscription('premium_plan', 'price_1QnFlYBGbzCnnsvRsaBS97pi')->create('pm_card_visa');
        $this->actingAs($user);

        $response = $this->get(route('reservations.index'));
        $response->assertStatus(200);
    }

    //ログイン済みの管理者は会員側の予約一覧ページにアクセスできない
    public function test_admin_cannot_access_reservation_index()
    {
 
        $admin = new Admin();
        $admin->email = 'admin@example.com';
        $admin->password = Hash::make('nagoyameshi');
        $admin->save();

        $response = $this->actingAs($admin, 'admin')->get(route('reservations.index'));
        $response->assertRedirect(route('admin.home'));
    }

    // 予約ページ

    //未ログインのユーザーは会員側の予約ページにアクセスできない
    public function test_guest_cannot_access_reservation_create()
    {
        $restaurant = Restaurant::factory()->create();

        $response = $this->get(route('restaurants.reservations.create', $restaurant));
        $response->assertRedirect(route('login'));
    }

    //ログイン済みの無料会員は会員側の予約ページにアクセスできない
    public function test_regular_user_can_access_reservation_create()
    {
        $user = User::factory()->create();
        $restaurant = Restaurant::factory()->create();

        $response = $this->actingAs($user)->get(route('restaurants.reservations.create', $restaurant));
        $response->assertRedirect(route('subscription.create'));
    }

    //ログイン済みの有料会員は会員側の予約ページにアクセスできる
    public function test_paid_user_can_access_reservation_create()
    {
        $user = User::factory()->create();
        $user->newSubscription('premium_plan', 'price_1QnFlYBGbzCnnsvRsaBS97pi')->create('pm_card_visa');
        $restaurant = Restaurant::factory()->create();

        $response = $this->actingAs($user)->get(route('restaurants.reservations.create', $restaurant));
        $response->assertStatus(200);
    }

    //ログイン済みの管理者は会員側の予約ページにアクセスできない
    public function test_admin_cannot_access_reservation_create()
    {
        $admin = new Admin();
        $admin->email = 'admin@example.com';
        $admin->password = Hash::make('nagoyameshi');
        $admin->save();

        $restaurant = Restaurant::factory()->create();

        $response = $this->actingAs($admin, 'admin')->get(route('restaurants.reservations.create', $restaurant));
        $response->assertRedirect(route('admin.home'));
    }

    // 予約機能
    //未ログインのユーザーは予約できない
    public function test_guest_cannot_store_reservation()
    {
        $user = User::factory()->create();
        $restaurant = Restaurant::factory()->create();
        $reservation = Reservation::factory()->create([
            'restaurant_id' => $restaurant->id,
            'user_id' => $user->id
        ]);

        $response = $this->post(route('restaurants.reservations.store', $restaurant), $reservation->toArray());
        $response->assertRedirect(route('login'));
    }

    //ログイン済みの無料会員は予約できない
    public function test_regular_user_cannot_store_reservation()
    {
        $user = User::factory()->create();
        $restaurant = Restaurant::factory()->create();
        $reservation = Reservation::factory()->create([
            'restaurant_id' => $restaurant->id,
            'user_id' => $user->id
        ]);

        $response = $this->post(route('restaurants.reservations.store', $restaurant), $reservation->toArray());
        $response->assertRedirect(route('subscription.create'));
    }

    //ログイン済みの有料会員は予約できる
    public function test_paid_user_can_store_reservation()
    {
        $user = User::factory()->create();
        $user->newSubscription('premium_plan', 'price_1QnFlYBGbzCnnsvRsaBS97pi')->create('pm_card_visa');
        $restaurant = Restaurant::factory()->create();
        $reservation = Reservation::factory()->create([
            'restaurant_id' => $restaurant->id,
            'user_id' => $user->id
        ]);

        $response = $this->post(route('restaurants.reservations.store', $restaurant), $reservation->toArray());
        $response->assertRedirect(route('reservations.index'));
    }

    //ログイン済みの管理者は予約できない
    public function test_admin_cannot_store_reservation()
    {
        $admin = new Admin();
        $admin->email = 'admin@example.com';
        $admin->password = Hash::make('nagoyameshi');
        $admin->save();

        $user = User::factory()->create(); 

        $restaurant = Restaurant::factory()->create();
        $reservation = Reservation::factory()->create([
            'restaurant_id' => $restaurant->id,
            'user_id' => $user->id
        ]);

        $response = $this->post(route('restaurants.reservations.store', $restaurant), $reservation->toArray());
        $response->assertRedirect(route('admin.home'));
    }


    // 予約キャンセル機能
    //未ログインのユーザーは予約をキャンセルできない
    public function test_guest_cannot_cancel_reservation()
    {
        $user = User::factory()->create();

        $restaurant = Restaurant::factory()->create();
        $reservation = Reservation::factory()->create([
            'restaurant_id' => $restaurant->id,
            'user_id' => $user->id
        ]);


        $response = $this->delete(route('reservations.destroy',$reservation));
        $response->assertRedirect(route('login'));
    }
    
    //ログイン済みの無料会員は予約をキャンセルできない
    public function test_regular_user_cannot_cancel_reservation()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $restaurant = Restaurant::factory()->create();
        $reservation = Reservation::factory()->create([
            'restaurant_id' => $restaurant->id,
            'user_id' => $user->id
        ]);

        $response = $this->actingAs($user)->delete(route('reservations.destroy',$reservation));
        $response->assertRedirect(route('subscription.create'));
    }

    //ログイン済みの有料会員は他人の予約をキャンセルできない
    public function test_paid_user_cannot_cancel_other_reservation()
    {
        $user = User::factory()->create();
        $user->newSubscription('premium_plan', 'price_1QnFlYBGbzCnnsvRsaBS97pi')->create('pm_card_visa');

        $otherUser = User::factory()->create();

        $restaurant = Restaurant::factory()->create();
        $reservation = Reservation::factory()->create([
            'restaurant_id' => $restaurant->id,
            'user_id' => $otherUser->id
        ]);

        $response = $this->delete(route('reservations.destroy',$reservation));
        $response->assertRedirect(route('reservations.index'));
    }

    //ログイン済みの有料会員は自身の予約をキャンセルできる
    public function test_paid_user_can_cancel_own_reservation()
    {
        $user = User::factory()->create();
        $user->newSubscription('premium_plan', 'price_1QnFlYBGbzCnnsvRsaBS97pi')->create('pm_card_visa');

        $restaurant = Restaurant::factory()->create();
        $reservation = Reservation::factory()->create([
            'restaurant_id' => $restaurant->id,
            'user_id' => $user->id
        ]);

        $response = $this->actingAs($user)->delete(route('reservations.destroy',$reservation));
        $response->assertRedirect(route('reservations.index'));
    }

    //ログイン済みの管理者は予約をキャンセルできない
    public function test_admin_cannot_cancel_reservation()
    {
        $admin = new Admin();
        $admin->email = 'admin@example.com';
        $admin->password = Hash::make('nagoyameshi');
        $admin->save();

        $user = User::factory()->create();
        $restaurant = Restaurant::factory()->create();
        $reservation = Reservation::factory()->create([
            'restaurant_id' => $restaurant->id,
            'user_id' => $user->id
        ]);

        $response = $this->actingAs($admin, 'admin')->delete(route('reservations.destroy',$reservation));
        $response->assertRedirect(route('admin.home'));
    }

}

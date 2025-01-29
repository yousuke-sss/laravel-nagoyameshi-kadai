<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Admin;
use App\Models\Restaurant;
use Illuminate\Support\Facades\Hash;

//未ログインのユーザーは会員側の店舗一覧ページにアクセスできる
//ユーザーは会員側の店舗一覧ページにアクセスできる
//ログイン済みの管理者は会員側の店舗一覧ページにアクセスできない

//未ログインのユーザーは会員側の店舗詳細ページにアクセスできる
//ログイン済みの一般ユーザーは会員側の店舗詳細ページにアクセスできる
//ログイン済みの管理者は会員側の店舗詳細ページにアクセスできない

class RestaurantTest extends TestCase
{
          /**
     *  @test
     */
    //未ログインのユーザーは会員側の店舗一覧ページにアクセスできる
    public function guest_can_access_restauran_index()
    {
        $response = $this->get(route('restaurants.index'));
        $response->assertStatus(200);
    }

           /**
     *  @test
     */
    //ユーザーは会員側の店舗一覧ページにアクセスできる
    public function user_can_access_restauran_index()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->actingAs($user)->get(route('restaurants.index'));
        $response->assertStatus(200);
    }

                 /**
     *  @test
     */
    //ログイン済みの管理者は会員側の店舗一覧ページにアクセスできない
    public function admin_cannot_access_restauran_index()
    {
        $admin = new Admin();
        $admin->email = 'admin@example.com';
        $admin->password = Hash::make('nagoyameshi');
        $admin->save();


        $response = $this->actingAs($admin, 'admin')->get(route('restaurants.index'));
        $response->assertRedirect(route('admin.home'));

    }

              /**
     *  @test
     */
    //未ログインのユーザーは会員側の店舗詳細ページにアクセスできる
    public function guest_can_access_restauran_show()
    {
        $restaurant = Restaurant::factory()->create();
        $response = $this->get(route('restaurants.show', $restaurant));
        $response->assertStatus(200);
    }

           /**
     *  @test
     */
    //ログイン済みの一般ユーザーは会員側の店舗詳細ページにアクセスできる
    public function user_can_access_restauran_show()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $restaurant = Restaurant::factory()->create();

        $response = $this->actingAs($user)->get(route('restaurants.show', $restaurant));
        $response->assertStatus(200);
    }

                    /**
     *  @test
     */
    //ログイン済みの管理者は会員側の店舗詳細ページにアクセスできない
    public function admin_cannot_access_restauran_show()
    {
        $admin = new Admin();
        $admin->email = 'admin@example.com';
        $admin->password = Hash::make('nagoyameshi');
        $admin->save();
        $restaurant = Restaurant::factory()->create();


        $response = $this->actingAs($admin, 'admin')->get(route('restaurants.show', $restaurant));
        $response->assertRedirect(route('admin.home'));
    }

}

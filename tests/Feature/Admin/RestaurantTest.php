<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use App\Models\Admin;
use App\Models\Restaurant;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RestaurantTest extends TestCase
{

    use RefreshDatabase;


    /**
     *  @test
     */
    //未ログインユーザーは店舗一覧ページにアクセスできない
    public function guest_cannot_access_admin_restaurant_index(): void
    {
        $response = $this->get(route('admin.restaurants.index'));
        $response->assertRedirect(route('admin.login'));
    }

    /**
     *  @test
     */
    //ログイン済みの一般ユーザーは管理者側の店舗一覧ページにアクセスできない
    public function user_cannot_access_admin_restaurant_index()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->actingAs($user)->get(route('admin.restaurants.index'));
        $response->assertRedirect(route('admin.login'));

    }

     /**
     *  @test
     */
    //ログイン済みの管理者は管理者側の店舗一覧ページにアクセスできる
    public function admin_can_access_admin_restaurant_index()
    {
        $admin = new Admin();
        $admin->email = 'admin@example.com';
        $admin->password = Hash::make('nagoyameshi');
        $admin->save();


        $response = $this->actingAs($admin, 'admin')->get(route('admin.restaurants.index'));
        $response->assertStatus(200);

    }

     /**
     *  @test
     */
    //未ログインユーザーは店舗詳細ページにアクセスできない
    public function guest_cannot_access_admin_restaurant_show()
    {
        $restaurant = Restaurant::factory()->create();

        $response = $this->get(route('admin.restaurants.show', $restaurant));
        $response->assertRedirect(route('admin.login'));

    }

    /**
     *  @test
     */
    //一般ユーザーは管理者側の店舗詳細ページにアクセスできない
    public function user_cannot_access_admin_restaurant_show()
    {
        $user = User::factory()->create();
        $restaurant = Restaurant::factory()->create();

        $response = $this->actingAs($user)->get(route('admin.restaurants.show', $restaurant));
        $response->assertRedirect(route('admin.login'));

    }

     /**
     *  @test
     */
    //ログイン済みの管理者は管理者側の店舗詳細ページにアクセスできる
    public function admin_can_access_admin_restaurant_show()
    {
        $admin = new Admin();
        $admin->email = 'admin@example.com';
        $admin->password = Hash::make('nagoyameshi');
        $admin->save();
        $restaurant = Restaurant::factory()->create();
        $response = $this->actingAs($admin, 'admin')->get(route('admin.restaurants.show', $restaurant));
        $response->assertStatus(200);

    }

     /**
     *  @test
     */
    //未ログインのユーザーは管理者側の店舗登録ページにアクセスできない
    public function guest_cannot_access_admin_restaurant_create()
    {      
        
        $response = $this->get(route('admin.restaurants.create'));
        $response->assertRedirect(route('admin.login'));
    }

    /**  @test */
    //ログイン済みの一般ユーザーは管理者側の店舗登録ページにアクセスできない
    public function user_cannot_access_admin_restaurant_create()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('admin.restaurants.create'));
        $response->assertRedirect(route('admin.login'));
    }

    /**  @test*/
    //ログイン済みの管理者は管理者側の店舗登録ページにアクセスできる
    public function admin_can_access_admin_restaurant_create()
    {
        $admin = new Admin();
        $admin->email = 'admin@example.com';
        $admin->password = Hash::make('nagoyameshi');
        $admin->save();

        $response = $this->actingAs($admin, 'admin')->get(route('admin.restaurants.create'));
        $response->assertStatus(200);

    }

    /**  @test */
    //未ログインのユーザーは店舗を登録できない
    public function guest_cannot_store_restaurant()
    {

        $response = $this->get(route('admin.restaurants.store'));
        $response->assertRedirect(route('admin.login'));

    }

    /**  @test*/
    //ログイン済みの一般ユーザーは店舗を登録できない
    public function user_cannot_store_restaurant()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get(route('admin.restaurants.store'));
        $response->assertRedirect(route('admin.login'));

    }

    /**  @test */
    //ログイン済みの管理者は店舗を登録できる
    public function admin_can_store_restaurant()
    {
        $admin = new Admin();
        $admin->email = 'admin@example.com';
        $admin->password = Hash::make('nagoyameshi');
        $admin->save();
        $restaurant = Restaurant::factory()->create();
        $response = $this->actingAs($admin, 'admin')->post(route('admin.restaurants.store'));
        $response->assertStatus(200);
    }

    /**  @test*/
    //未ログインのユーザーは管理者側の店舗編集ページにアクセスできない
    public function guest_cannot_access_admin_restaurant_edit()
    {
        $restaurant = Restaurant::factory()->create();
        $response = $this->get(route('admin.restaurants.edit', $restaurant));
        $response->assertRedirect(route('admin.login'));

    }

    /**  @test */
    //ログイン済みの一般ユーザーは管理者側の店舗編集ページにアクセスできない
    public function user_cannot_access_admin_restaurant_edit()
    {
        $user = User::factory()->create();
        $restaurant = Restaurant::factory()->create();

        $response = $this->actingAs($user)->get(route('admin.restaurants.edit', $restaurant));
        $response->assertRedirect(route('admin.login'));

    }

    /** @test */
    //ログイン済みの管理者は管理者側の店舗編集ページにアクセスできる
    public function admin_can_access_admin_restaurant_edit()
    {
        $admin = new Admin();
        $admin->email = 'admin@example.com';
        $admin->password = Hash::make('nagoyameshi');
        $admin->save();
        $restaurant = Restaurant::factory()->create();
        $response = $this->actingAs($admin, 'admin')->get(route('admin.restaurants.edit', $restaurant));
        $response->assertStatus(200);

    }

    /**  @test */
    //未ログインのユーザーは店舗を更新できない
    public function guest_cannot_update_restaurant()
    {

        $restaurant = Restaurant::factory()->create();
        $response = $this->patch(route('admin.restaurants.update', $restaurant));
        $response->assertRedirect(route('admin.login'));

    }

    /**  @test*/
    //ログイン済みの一般ユーザーは店舗を更新できない 
    public function user_cannot_update_restaurant()
    {
        $user = User::factory()->create();
        $restaurant = Restaurant::factory()->create();

        $response = $this->actingAs($user)->patch(route('admin.restaurants.update', $restaurant));
        $response->assertRedirect(route('admin.login'));

    }

    /**  @test */
    //ログイン済みの管理者は店舗を更新できる
    public function admin_can_update_restaurant()
    {
        $admin = new Admin();
        $admin->email = 'admin@example.com';
        $admin->password = Hash::make('nagoyameshi');
        $admin->save();
        $restaurant = Restaurant::factory()->create();
        $response = $this->actingAs($admin, 'admin')->patch(route('admin.restaurants.update', $restaurant));
        $response->assertStatus(200);

    }

    /**  @test */
    //未ログインのユーザーは店舗を削除できない
    public function guest_cannot_delete_restaurant()
    {

        $restaurant = Restaurant::factory()->create();
        $response = $this->delete(route('admin.restaurants.destroy', $restaurant));
        $response->assertRedirect(route('admin.login'));

    }

    /**  @test*/
    //ログイン済みの一般ユーザーは店舗を削除できない
    public function user_cannot_delete_restaurant()
    {
        $user = User::factory()->create();
        $restaurant = Restaurant::factory()->create();

        $response = $this->actingAs($user)->delete(route('admin.restaurants.destroy', $restaurant));
        $response->assertRedirect(route('admin.login'));

    }

    /**  @test */
    //ログイン済みの管理者は店舗を削除できる
    public function admin_can_delete_restaurant()
    {
        $admin = new Admin();
        $admin->email = 'admin@example.com';
        $admin->password = Hash::make('nagoyameshi');
        $admin->save();
        $restaurant = Restaurant::factory()->create();
        $response = $this->actingAs($admin, 'admin')->delete(route('admin.restaurants.destroy', $restaurant));
        $response->assertStatus(200);

    }
}

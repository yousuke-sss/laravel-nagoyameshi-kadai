<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use App\Models\Admin;
use App\Models\Restaurant;
use App\Models\Category;
use App\Models\RegularHoliday;
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

        $restaurant_data = [
            'name' => 'テスト',
            'description' => 'テスト',
            'lowest_price' => 1000,
            'highest_price' => 5000,
            'postal_code' => '0000000',
            'address' => 'テスト',
            'opening_time' => '10:00:00',
            'closing_time' => '20:00:00',
            'seating_capacity' => 50
        ];

        $response = $this->post(route('admin.restaurants.store'), $restaurant_data);

        $this->assertDatabaseMissing('restaurants', $restaurant_data);

        $response->assertRedirect(route('admin.login'));
    }

    /**  @test*/
    //ログイン済みの一般ユーザーは店舗を登録できない
    public function user_cannot_store_restaurant()
    {
        $user = User::factory()->create();
        $restaurant_data = [
            'name' => 'テスト',
            'description' => 'テスト',
            'lowest_price' => 1000,
            'highest_price' => 5000,
            'postal_code' => '0000000',
            'address' => 'テスト',
            'opening_time' => '10:00:00',
            'closing_time' => '20:00:00',
            'seating_capacity' => 50
        ];

        $response = $this->actingAs($user)->post(route('admin.restaurants.store'), $restaurant_data);

        $this->assertDatabaseMissing('restaurants', $restaurant_data);

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

        $categories = Category::factory()->count(3)->create();
        $category_ids = $categories->pluck('id')->toArray();

         //休日
         $regularholidays = RegularHoliday::factory()->count(3)->create();
         $regular_holiday_ids = $regularholidays->pluck('id')->toArray();
        

        $new_restaurant_data = [
            'name' => 'テスト',
            'description' => 'テスト',
            'lowest_price' => 1000,
            'highest_price' => 5000,
            'postal_code' => '0000000',
            'address' => 'テスト',
            'opening_time' => '10:00',
            'closing_time' => '20:00',
            'seating_capacity' => 50,
            'category_ids' => $category_ids,
            'regular_holiday_ids' => $regular_holiday_ids,
        ];

        $response = $this->actingAs($admin, 'admin')->post(route('admin.restaurants.store'),  $new_restaurant_data);

        // category_idsを除外したデータを用意
        $restaurant_data_without_categories = $new_restaurant_data;
        unset($restaurant_data_without_categories['category_ids']);

        // regular_holiday_idsを除外したデータを用意
        $restaurant_data_without_regularholidays = $new_restaurant_data;
        unset($restaurant_data_without_regularholidays['regular_holiday_ids']);

        unset($new_restaurant_data['category_ids'], $new_restaurant_data['regular_holiday_ids']);
        $this->assertDatabaseHas('restaurants', $new_restaurant_data);


        $restaurant = Restaurant::latest('id')->first();
        //カテゴリ
        foreach ($category_ids as $category_id) {
            $this->assertDatabaseHas('category_restaurant', ['restaurant_id' => $restaurant->id, 'category_id' => $category_id]);
        }
        //定休日
        foreach ($regular_holiday_ids as $regular_holiday_id) {
            $this->assertDatabaseHas('regular_holiday_restaurant', ['restaurant_id' => $restaurant->id, 'regular_holiday_id' => $regular_holiday_id]);
        }

        $response->assertRedirect(route('admin.restaurants.index'));

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
        $old_restaurant = Restaurant::factory()->create();

        $new_restaurant_data = [
            'name' => 'テスト更新',
            'description' => 'テスト更新',
            'lowest_price' => 5000,
            'highest_price' => 10000,
            'postal_code' => '1234567',
            'address' => 'テスト更新',
            'opening_time' => '13:00:00',
            'closing_time' => '23:00:00',
            'seating_capacity' => 100
        ];

        $response = $this->patch(route('admin.restaurants.update', $old_restaurant), $new_restaurant_data);

        $this->assertDatabaseMissing('restaurants', $new_restaurant_data);

        $response->assertRedirect(route('admin.login'));
    }

    /**  @test*/
    //ログイン済みの一般ユーザーは店舗を更新できない 
    public function user_cannot_update_restaurant()
    {
        $user = User::factory()->create();

        $old_restaurant = Restaurant::factory()->create();

        $new_restaurant_data = [
            'name' => 'テスト更新',
            'description' => 'テスト更新',
            'lowest_price' => 5000,
            'highest_price' => 10000,
            'postal_code' => '1234567',
            'address' => 'テスト更新',
            'opening_time' => '13:00',
            'closing_time' => '23:00',
            'seating_capacity' => 100
        ];

        $response = $this->actingAs($user)->patch(route('admin.restaurants.update', $old_restaurant), $new_restaurant_data);

        $this->assertDatabaseMissing('restaurants', $new_restaurant_data);

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

        $old_restaurant = Restaurant::factory()->create();

        $categories = Category::factory()->count(3)->create();
        $category_ids = $categories->pluck('id')->toArray();
         //休日
         $regularholidays = RegularHoliday::factory()->count(3)->create();
         $regular_holiday_ids = $regularholidays->pluck('id')->toArray();

        $new_restaurant_data = [
            'name' => 'テスト',
            'description' => 'テスト',
            'lowest_price' => 1000,
            'highest_price' => 5000,
            'postal_code' => '0000000',
            'address' => 'テスト',
            'opening_time' => '10:00',
            'closing_time' => '20:00',
            'seating_capacity' => 50,
            'category_ids' => $category_ids,
            'regular_holiday_ids' => $regular_holiday_ids,

        ];

        $response = $this->actingAs($admin, 'admin')->patch(route('admin.restaurants.update', $old_restaurant), $new_restaurant_data);

        // category_idsを除外したデータを用意
        $restaurant_data_without_categories = $new_restaurant_data;
        unset($restaurant_data_without_categories['category_ids']);

         // regular_holiday_idsを除外したデータを用意
         $restaurant_data_without_regularholidays = $new_restaurant_data;
         unset($restaurant_data_without_regularholidays['regular_holiday_ids']);

         unset($new_restaurant_data['category_ids'], $new_restaurant_data['regular_holiday_ids']);
         $this->assertDatabaseHas('restaurants', $new_restaurant_data);

        $restaurant = Restaurant::latest('id')->first();
        //カテゴリ
        foreach ($category_ids as $category_id) {
            $this->assertDatabaseHas('category_restaurant', ['restaurant_id' => $restaurant->id, 'category_id' => $category_id]);
        }

        //定休日
        foreach ($regular_holiday_ids as $regular_holiday_id) {
            $this->assertDatabaseHas('regular_holiday_restaurant', ['restaurant_id' => $restaurant->id, 'regular_holiday_id' => $regular_holiday_id]);
        }

        $response->assertRedirect(route('admin.restaurants.show', $old_restaurant));
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
        $this->assertDatabaseMissing('restaurants', ['id' => $restaurant->id]);
        $response->assertRedirect(route('admin.restaurants.index'));


    }
}

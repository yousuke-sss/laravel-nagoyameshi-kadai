<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Admin;
use App\Models\Restaurant;
use App\Models\Category;
use Illuminate\Support\Facades\Hash;

class HomeTest extends TestCase
{

    use RefreshDatabase;

      /**
     *  @test
     */
    //未ログインのユーザーは会員側のトップページにアクセスできる
    public function gest_can_access_user_home_index()
    {

        $response = $this->get(route('heme'));
        $response->assertStatus(200);

    }

        /**
     *  @test
     */
    //ログイン済みの一般ユーザーは会員側のトップページにアクセスできる
    public function user_can_access_user_home_index()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->actingAs($user)->get(route('heme'));
        $response->assertStatus(200);

    }

         /**
     *  @test
     */
    //ログイン済みの管理者は会員側のトップページにアクセスできない
    public function admin_cannot_access_user_home_index()
    {
        $admin = new Admin();
        $admin->email = 'admin@example.com';
        $admin->password = Hash::make('nagoyameshi');
        $admin->save();


        $response = $this->actingAs($admin, 'admin')->get(route('heme'));
        $response->assertRedirect(route('admin.home'));

    }
}

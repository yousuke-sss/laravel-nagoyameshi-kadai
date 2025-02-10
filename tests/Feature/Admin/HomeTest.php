<?php

namespace Tests\Feature\Admin;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Restaurant;
use App\Models\Reservation;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;


class HomeTest extends TestCase
{
    use RefreshDatabase;

    /**
   *  @test
   */
  //未ログインのユーザーは管理者側のトップページにアクセスできない
  public function gest_cannot_access_adomin_home_index()
  {

      $response = $this->get(route('admin.home'));
      $response->assertRedirect(route('admin.login'));

  }

          /**
     *  @test
     */
    //ログイン済みの一般ユーザーは管理者側のトップページにアクセスでない
    public function user_cannot_access_admin_home_index()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('admin.home'));
        $response->assertRedirect(route('admin.login'));
    }

         /**
     *  @test
     */
    //ログイン済みの管理者は管理者側のトップページにアクセスできる
    public function admin_can_access_admin_home_index()
    {
        $admin = new Admin();
        $admin->email = 'admin@example.com';
        $admin->password = Hash::make('nagoyameshi');
        $admin->save();


        $response = $this->actingAs($admin, 'admin')->get(route('admin.home'));
        $response->assertStatus(200);

    }
}

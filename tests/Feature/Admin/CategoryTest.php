<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use App\Models\Admin;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    use RefreshDatabase;

    /** @test*/
    //未ログインユーザーはカテゴリ一覧ページにアクセスできない
    public function guest_cannot_access_admin_category_index(): void
    {
        $response = $this->get(route('admin.categories.index'));
        $response->assertRedirect(route('admin.login'));
    }

    /** @test*/
    //ログイン済みの一般ユーザーは管理者側のカテゴリ一覧ページにアクセスできない
    public function user_cannot_access_admin_category_index()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->actingAs($user)->get(route('admin.categories.index'));
        $response->assertRedirect(route('admin.login'));
    }

    /** @test*/
    //ログイン済みの管理者は管理者側のカテゴリ一覧ページにアクセスできる
    public function admin_can_access_admin_category_index()
    {
        $admin = new Admin();
        $admin->email = 'admin@example.com';
        $admin->password = Hash::make('nagoyameshi');
        $admin->save();

        $response = $this->actingAs($admin, 'admin')->get(route('admin.categories.index'));
        $response->assertStatus(200);
    }

    /** @test */
    //未ログインのユーザーはカテゴリを登録できない
    public function guest_cannot_store_category()
    {
        $response = $this->post(route('admin.categories.store'));
        $response->assertRedirect(route('admin.login'));
    }

    /** @test*/
    //ログイン済みの一般ユーザーはカテゴリを登録できない
    public function user_cannot_store_category()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->post(route('admin.categories.store'));
        $response->assertRedirect(route('admin.login'));
    }

    /**  @test */
    //ログイン済みの管理者はカテゴリを登録できる
    public function admin_can_store_category()
    {
        $admin = new Admin();
        $admin->email = 'admin@example.com';
        $admin->password = Hash::make('nagoyameshi');
        $admin->save();
        $category = Category::factory()->create()->toArray();
        $response = $this->actingAs($admin, 'admin')->post(route('admin.categories.store'), $category);
        $this->assertDatabaseHas('categories', ['name' => $category['name']]);
        $response->assertRedirect(route('admin.categories.index'));
    }

    /**  @test */
    //未ログインのユーザーはカテゴリを更新できない
    public function guest_cannot_update_category()
    {
        $category = Category::factory()->create();
        $response = $this->patch(route('admin.categories.update', $category));
        $response->assertRedirect(route('admin.login'));
    }

    /**  @test*/
    //ログイン済みの一般ユーザーはカテゴリを更新できない 
    public function user_cannot_update_category()
    {
        $user = User::factory()->create();
        $category = Category::factory()->create();

        $response = $this->actingAs($user)->patch(route('admin.categories.update', $category));
        $response->assertRedirect(route('admin.login'));
    }

    /**  @test */
    //ログイン済みの管理者はカテゴリを更新できる
    public function admin_can_update_category()
    {
        $admin = new Admin();
        $admin->email = 'admin@example.com';
        $admin->password = Hash::make('nagoyameshi');
        $admin->save();
        $category = Category::factory()->create();
        $new_category_data = ['name' => 'テスト更新',];
       
        $response = $this->actingAs($admin, 'admin')->patch(route('admin.categories.update', $category), $new_category_data);

        $this->assertDatabaseHas('categories', $new_category_data);
        $response->assertRedirect(route('admin.categories.index'));
    }
    
    /**  @test */
    //未ログインのユーザーはカテゴリを削除できない
    public function guest_cannot_delete_category()
    {

        $category = Category::factory()->create();
        $response = $this->delete(route('admin.categories.destroy', $category));
        $response->assertRedirect(route('admin.login'));

    }

    /**  @test*/
    //ログイン済みの一般ユーザーはカテゴリを削除できない
    public function user_cannot_delete_category()
    {
        $user = User::factory()->create();
        $category = Category::factory()->create();

        $response = $this->actingAs($user)->delete(route('admin.categories.destroy', $category));
        $response->assertRedirect(route('admin.login'));

    }

    /**  @test */
    //ログイン済みの管理者はカテゴリを削除できる
    public function admin_can_delete_category()
    {
        $admin = new Admin();
        $admin->email = 'admin@example.com';
        $admin->password = Hash::make('nagoyameshi');
        $admin->save();
        $category = Category::factory()->create();
        $response = $this->actingAs($admin, 'admin')->delete(route('admin.categories.destroy', $category));
        $this->assertDatabaseMissing('categories', ['id' => $category->id]);
        $response->assertRedirect(route('admin.categories.index'));
    }

}

<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use App\Models\Admin;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /**
     * 未ログインユーザーは会員一覧ページにアクセスできない
     */
    public function test_guest_cannot_access_users_index(): void
    {
        $response = $this->get('/admin/users');
        $response->assertRedirect('admin/login');
    }

    /**
     * 一般ユーザーは管理者側の会員一覧ページにアクセスできない
     */
    public function test_user_cannot_access_users_index(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get('/admin/users');
        $response->assertForbidden();
    }

    /**
     * ログイン済みの管理者は管理者側の会員一覧ページにアクセスできる
     */
    public function test_admin_can_access_users_index(): void
    {
        $admin = Admin::factory()->create();
        $this->actingAs($admin, 'admin');

        $response = $this->get('/admin/users');
        $response->assertStatus(200);
    }

    /**
     * 未ログインユーザーは会員詳細ページにアクセスできない
     */
    public function test_guest_cannot_access_user_show(): void
    {
        $response = $this->get('/admin/users/1');
        $response->assertRedirect('/login');
    }

    /**
     * 一般ユーザーは管理者側の会員詳細ページにアクセスできない
     */
    public function test_user_cannot_access_user_show(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get('/admin/users/1');
        $response->assertForbidden();
    }

    /**
     * ログイン済みの管理者は管理者側の会員詳細ページにアクセスできる
     */
    public function test_admin_can_access_user_show(): void
    {
        $admin = Admin::factory()->create();
        $this->actingAs($admin, 'admin');

        $user = User::factory()->create();
        $response = $this->get("/admin/users/{$user->id}");
        $response->assertStatus(200);
    }
}

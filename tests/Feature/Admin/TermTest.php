<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use App\Models\Admin;
use App\Models\Term;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class TermTest extends TestCase
{
    use RefreshDatabase;

    /**
     *  @test
     */
    //未ログインのユーザーは管理者側の利用規約ページにアクセスできない
    public function guest_cannot_access_admin_term_index(): void
    {
        $response = $this->get(route('admin.terms.index'));
        $response->assertRedirect(route('admin.login'));
    }

    /**
     *  @test
     */
    //ログイン済みの一般ユーザーは管理者側の利用規約ページにアクセスできない
    public function user_cannot_access_admin_term_index()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('admin.terms.index'));
        $response->assertRedirect(route('admin.login'));

    }

     /**
     *  @test
     */
    //ログイン済みの管理者は管理者側の利用規約ページにアクセスできる
    public function admin_can_access_admin_term_index()
    {
        $admin = new Admin();
        $admin->email = 'admin@example.com';
        $admin->password = Hash::make('nagoyameshi');
        $admin->save();

        $term = Term::factory()->create();

        $response = $this->actingAs($admin, 'admin')->get(route('admin.terms.index'));
        $response->assertStatus(200);
    }

     /**  @test*/
    //未ログインのユーザーは管理者側の利用規約編集ページにアクセスできない
    public function guest_cannot_access_admin_term_edit()
    {
        $term = Term::factory()->create();
        $response = $this->get(route('admin.terms.edit', $term));
        $response->assertRedirect(route('admin.login'));

    }

       /**  @test */
    //ログイン済みの一般ユーザーは管理者側の利用規約編集ページにアクセスできない
    public function user_cannot_access_admin_term_edit()
    {
        $user = User::factory()->create();
        $term = Term::factory()->create();

        $response = $this->actingAs($user)->get(route('admin.terms.edit', $term));
        $response->assertRedirect(route('admin.login'));

    }

        /** @test */
    //ログイン済みの管理者は管理者側の利用規約編集ページにアクセスできる
    public function admin_can_access_admin_term_edit()
    {
        $admin = new Admin();
        $admin->email = 'admin@example.com';
        $admin->password = Hash::make('nagoyameshi');
        $admin->save();
        $term = Term::factory()->create();
        $response = $this->actingAs($admin, 'admin')->get(route('admin.terms.edit', $term));
        $response->assertStatus(200);

    }

        /**  @test */
    //未ログインのユーザーは利用規約を更新できない
    public function guest_cannot_update_term()
    {
        $old_term = Term::factory()->create();

        $new_term_data = [
            'content' => 'テスト更新',
        ];

        $response = $this->patch(route('admin.terms.update', $old_term), $new_term_data);

        $this->assertDatabaseMissing('terms', $new_term_data);

        $response->assertRedirect(route('admin.login'));
    }

        /**  @test*/
    //ログイン済みの一般ユーザーは利用規約を更新できない 
    public function user_cannot_update_term()
    {
        $user = User::factory()->create();

        $old_term = Term::factory()->create();

        $new_term_data = [
            'content' => 'テスト更新',
        ];

        $response = $this->actingAs($user)->patch(route('admin.terms.update', $old_term), $new_term_data);

        $this->assertDatabaseMissing('terms', $new_term_data);

        $response->assertRedirect(route('admin.login'));
    }

        /**  @test */
    //ログイン済みの管理者は店舗を更新できる
    public function admin_can_update_term()
    {

        $admin = new Admin();
        $admin->email = 'admin@example.com';
        $admin->password = Hash::make('nagoyameshi');
        $admin->save();

        $old_term = Term::factory()->create();

        $new_term_data = [
            'content' => 'テスト更新',
        ];
        
        $response = $this->actingAs($admin, 'admin')->patch(route('admin.terms.update', $old_term), $new_term_data);
        $response->assertRedirect(route('admin.terms.index'));
        $this->assertDatabaseHas('terms', $new_term_data);
    }

}

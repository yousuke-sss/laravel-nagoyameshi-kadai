<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use App\Models\Admin;
use App\Models\Company;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class CompanyTest extends TestCase
{
    use RefreshDatabase;

    /**
     *  @test
     */
    //未ログインのユーザーは管理者側の会社概要ページにアクセスできない
    public function guest_cannot_access_admin_company_index(): void
    {
        $response = $this->get(route('admin.company.index'));
        $response->assertRedirect(route('admin.login'));
    }

    /**
     *  @test
     */
    //ログイン済みの一般ユーザーは管理者側の会社概要ページにアクセスできない
    public function user_cannot_access_admin_company_index()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('admin.company.index'));
        $response->assertRedirect(route('admin.login'));

    }

     /**
     *  @test
     */
    //ログイン済みの管理者は管理者側の会社概要ページにアクセスできる
    public function admin_can_access_admin_company_index()
    {
        $admin = new Admin();
        $admin->email = 'admin@example.com';
        $admin->password = Hash::make('nagoyameshi');
        $admin->save();

        $company = Company::factory()->create();

        $response = $this->actingAs($admin, 'admin')->get(route('admin.company.index'));
        $response->assertStatus(200);
    }

     /**  @test*/
    //未ログインのユーザーは管理者側の会社概要編集ページにアクセスできない
    public function guest_cannot_access_admin_company_edit()
    {
        $company = Company::factory()->create();
        $response = $this->get(route('admin.company.edit', $company));
        $response->assertRedirect(route('admin.login'));
    }

       /**  @test */
    //ログイン済みの一般ユーザーは管理者側の会社概要編集ページにアクセスできない
    public function user_cannot_access_admin_company_edit()
    {
        $user = User::factory()->create();
        $company = Company::factory()->create();

        $response = $this->actingAs($user)->get(route('admin.company.edit', $company));
        $response->assertRedirect(route('admin.login'));

    }

        /** @test */
    //ログイン済みの管理者は管理者側の会社概要編集ページにアクセスできる
    public function admin_can_access_admin_company_edit()
    {
        $admin = new Admin();
        $admin->email = 'admin@example.com';
        $admin->password = Hash::make('nagoyameshi');
        $admin->save();
        $company = Company::factory()->create();
        $response = $this->actingAs($admin, 'admin')->get(route('admin.company.edit', $company));
        $response->assertStatus(200);

    }

        /**  @test */
    //未ログインのユーザーは会社概要を更新できない
    public function guest_cannot_update_company()
    {
        $old_company = Company::factory()->create();
        $new_company_data = [
            'name' => 'テスト更新',
            'postal_code' => '1111111',
            'address' => 'テスト更新',
            'representative' => 'テスト更新',
            'establishment_date' => 'テスト更新',
            'capital' => 'テスト更新',
            'business' => 'テスト更新',
            'number_of_employees' => 'テスト更新',
        ];

        $response = $this->patch(route('admin.company.update', $old_company), $new_company_data);

        $this->assertDatabaseMissing('companies', $new_company_data);

        $response->assertRedirect(route('admin.login'));
    }

        /**  @test*/
    //ログイン済みの一般ユーザーは会社概要を更新できない 
    public function user_cannot_update_company()
    {
        $user = User::factory()->create();

        $old_company = Company::factory()->create();
        
        $new_company_data = [
            'name' => 'テスト更新',
            'postal_code' => '1111111',
            'address' => 'テスト更新',
            'representative' => 'テスト更新',
            'establishment_date' => 'テスト更新',
            'capital' => 'テスト更新',
            'business' => 'テスト更新',
            'number_of_employees' => 'テスト更新',
        ];
        $response = $this->patch(route('admin.company.update', $old_company), $new_company_data);

        $this->assertDatabaseMissing('companies', $new_company_data);

        $response->assertRedirect(route('admin.login'));
    }

        /**  @test */
    //ログイン済みの管理者は会社概要を更新できる
    public function admin_can_update_company()
    {

        $admin = new Admin();
        $admin->email = 'admin@example.com';
        $admin->password = Hash::make('nagoyameshi');
        $admin->save();

        $old_company = Company::factory()->create();
        
        $new_company_data = [
            'name' => 'テスト更新',
            'postal_code' => '1111111',
            'address' => 'テスト更新',
            'representative' => 'テスト更新',
            'establishment_date' => 'テスト更新',
            'capital' => 'テスト更新',
            'business' => 'テスト更新',
            'number_of_employees' => 'テスト更新',
        ];

        $response = $this->actingAs($admin, 'admin')->patch(route('admin.company.update', $old_company), $new_company_data);

        $response->assertRedirect(route('admin.company.index'));
        $this->assertDatabaseHas('companies', $new_company_data);
    }

}


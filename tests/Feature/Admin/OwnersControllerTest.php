<?php

namespace Tests\Feature\Admin;

use App\Models\Admin;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Owner;
use Exception;

class OwnersControllerTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    //$this->assertAuthenticated($guard = null);
    use RefreshDatabase;

    protected $seed = true;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = Admin::factory()->create();
    }
    public function test_login_screen_can_be_rendered()
    {
        $response = $this->get(route('admin.login'));
        $response->assertStatus(200);
    }

    public function test_users_can_authenticate_using_the_login_screen()
    {
        $this->actingAs($this->admin, 'admin')->get(route('admin.dashboard'))->assertSuccessful();
        $this->actingAs($this->admin, 'admin')->get(route('admin.owners.index'))->assertSuccessful();
        $this->actingAs($this->admin, 'admin')->get(route('admin.owners.create'))->assertSuccessful();
        $this->actingAs($this->admin, 'admin')->get(route('admin.expired-owners.index'))->assertSuccessful();
    }

    public function test_create_owner()
    {
        $owner = [
            'name' => 'testUser',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123'
        ];

        $responese = $this->actingAs($this->admin, 'admin')->post(route('admin.owners.store'), $owner);
        $responese->assertRedirect(route('admin.owners.index'));

        $owner_same_email = [
            'name' => 'testUser2',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123'
        ];
        $this->expectException(Exception::class);
        $this->withoutExceptionHandling()->actingAs($this->admin, 'admin')->post(route('admin.owners.store'), $owner_same_email);

        $owner_email_validate = [
            'name' => 'testUser3',
            'email' => 'test_error',
            'password' => 'password123',
            'password_confirmation' => 'password123'
        ];
        $this->withoutExceptionHandling()->actingAs($this->admin, 'admin')->post(route('admin.owners.store'), $owner_email_validate)->assertInValid();
    }

    public function test_delete_owner()
    {
        //ソフトデリート
        $owner = Owner::factory()->create();
        $this->assertDatabaseHas('owners', ['id' => $owner->id]);
        $response = $this->actingAs($this->admin, 'admin')->delete(route('admin.owners.destroy', $owner->id), ['id' => $owner->id]);
        $response->assertRedirect(route('admin.owners.index'));
        $this->assertSoftDeleted($owner);

        //期限切れオーナー一覧
        $response_expired = $this->actingAs($this->admin, 'admin')->get(route('admin.expired-owners.index'));
        $response_expired->assertSuccessful();
        $response_expired->assertSee($owner->name);

        $response_expired_delete = $this->actingAs($this->admin, 'admin')->post(route('admin.expired-owners.destroy', $owner->id), ['id' => $owner->id]);
        $response_expired_delete->assertRedirect(route('admin.expired-owners.index'));
        $this->assertDatabaseMissing('owners', ['id' => $owner->id]);
    }
    /* 
    public function test_create_shop()
    {
        $owner = Owner::factory()->create();
        $shop = [
            'owner_id' => $owner->id,
            'name' => 'テスト',
            "information" => 'テストテスト',
            'firstname' => 'テスト太郎',
            'is_selling' => true
        ];

        $response = $this->actingAs($this->admin, 'admin')->post(route('admin.owners.store'), $shop);
        $response->assertRedirect(route('admin.owners.index'));
    } */
}

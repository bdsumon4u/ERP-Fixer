<?php

namespace Tests\Feature\Tenancy\Admin;

use App\Models\Admin;
use Tests\RefreshTenantDatabase as RefreshDatabase;
use Tests\TestCase;

class PasswordConfirmationTest extends TestCase
{
    use RefreshDatabase;

    protected ?string $guard = 'admin';

    protected bool $tenancy = true;

    public function test_confirm_password_screen_can_be_rendered()
    {
        $admin = Admin::factory()->create();

        $response = $this->actingAs($admin, $this->guard)->get('/user/confirm-password');

        $response->assertStatus(200);
    }

    public function test_password_can_be_confirmed()
    {
        $admin = Admin::factory()->create();

        $response = $this->actingAs($admin, $this->guard)->post('/user/confirm-password', [
            'password' => 'password',
        ]);

        $response->assertRedirect();
        $response->assertSessionHasNoErrors();
    }

    public function test_password_is_not_confirmed_with_invalid_password()
    {
        $admin = Admin::factory()->create();

        $response = $this->actingAs($admin, $this->guard)->post('/user/confirm-password', [
            'password' => 'wrong-password',
        ]);

        $response->assertSessionHasErrors();
    }
}

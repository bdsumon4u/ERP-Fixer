<?php

namespace Tests\Feature\Tenancy\Admin;

use App\Models\Admin;
use App\Providers\RouteServiceProvider;
use Tests\RefreshTenantDatabase as RefreshDatabase;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    protected ?string $guard = 'admin';

    protected bool $tenancy = true;

    public function test_login_screen_can_be_rendered()
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
    }

    public function test_admins_can_authenticate_using_the_login_screen()
    {
        $admin = Admin::factory()->create();

        $response = $this->post('/login', [
            'email' => $admin->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticated($this->guard);
        $response->assertRedirect(RouteServiceProvider::HOME);
    }

    public function test_admins_can_not_authenticate_with_invalid_password()
    {
        $admin = Admin::factory()->create();

        $this->post('/login', [
            'email' => $admin->email,
            'password' => 'wrong-password',
        ]);

        $this->assertGuest();
    }
}

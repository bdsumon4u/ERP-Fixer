<?php

namespace Tests\Feature\Tenancy\Seller;

use App\Models\Seller;
use App\Providers\RouteServiceProvider;
use Tests\RefreshTenantDatabase as RefreshDatabase;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    protected ?string $guard = 'seller';

    protected bool $tenancy = true;

    public function test_login_screen_can_be_rendered()
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
    }

    public function test_sellers_can_authenticate_using_the_login_screen()
    {
        $seller = Seller::factory()->create();

        $response = $this->post('/login', [
            'email' => $seller->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticated($this->guard);
        $response->assertRedirect(RouteServiceProvider::HOME);
    }

    public function test_sellers_can_not_authenticate_with_invalid_password()
    {
        $seller = Seller::factory()->create();

        $this->post('/login', [
            'email' => $seller->email,
            'password' => 'wrong-password',
        ]);

        $this->assertGuest();
    }
}

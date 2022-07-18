<?php

namespace Tests\Feature\Tenancy\Seller;

use App\Models\Seller;
use Tests\RefreshTenantDatabase as RefreshDatabase;
use Tests\TestCase;

class PasswordConfirmationTest extends TestCase
{
    use RefreshDatabase;

    protected ?string $guard = 'seller';

    protected bool $tenancy = true;

    public function test_confirm_password_screen_can_be_rendered()
    {
        $seller = Seller::factory()->create();

        $response = $this->actingAs($seller, $this->guard)->get('/user/confirm-password');

        $response->assertStatus(200);
    }

    public function test_password_can_be_confirmed()
    {
        $seller = Seller::factory()->create();

        $response = $this->actingAs($seller, $this->guard)->post('/user/confirm-password', [
            'password' => 'password',
        ]);

        $response->assertRedirect();
        $response->assertSessionHasNoErrors();
    }

    public function test_password_is_not_confirmed_with_invalid_password()
    {
        $seller = Seller::factory()->create();

        $response = $this->actingAs($seller, $this->guard)->post('/user/confirm-password', [
            'password' => 'wrong-password',
        ]);

        $response->assertSessionHasErrors();
    }
}

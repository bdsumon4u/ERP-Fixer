<?php

namespace Tests\Feature\Tenancy\Seller;

use App\Models\Seller;
use Illuminate\Support\Facades\Hash;
use Tests\RefreshTenantDatabase as RefreshDatabase;
use Tests\TestCase;

class UpdatePasswordTest extends TestCase
{
    use RefreshDatabase;

    protected ?string $guard = 'seller';

    protected bool $tenancy = true;

    public function test_password_can_be_updated()
    {
        $this->actingAs($seller = Seller::factory()->create(), $this->guard);

        $response = $this->put('/user/password', [
            'current_password' => 'password',
            'password' => 'new-password',
            'password_confirmation' => 'new-password',
        ]);

        $this->assertTrue(Hash::check('new-password', $seller->fresh()->password));
    }

    public function test_current_password_must_be_correct()
    {
        $this->actingAs($seller = Seller::factory()->create(), $this->guard);

        $response = $this->put('/user/password', [
            'current_password' => 'wrong-password',
            'password' => 'new-password',
            'password_confirmation' => 'new-password',
        ]);

        $response->assertSessionHasErrors();

        $this->assertTrue(Hash::check('password', $seller->fresh()->password));
    }

    public function test_new_passwords_must_match()
    {
        $this->actingAs($seller = Seller::factory()->create(), $this->guard);

        $response = $this->put('/user/password', [
            'current_password' => 'password',
            'password' => 'new-password',
            'password_confirmation' => 'wrong-password',
        ]);

        $response->assertSessionHasErrors();

        $this->assertTrue(Hash::check('password', $seller->fresh()->password));
    }
}

<?php

namespace Tests\Feature\Tenancy\Seller;

use App\Models\Seller;
use Laravel\Jetstream\Features;
use Tests\RefreshTenantDatabase as RefreshDatabase;
use Tests\TestCase;

class DeleteAccountTest extends TestCase
{
    use RefreshDatabase;

    protected ?string $guard = 'seller';

    protected bool $tenancy = true;

    public function test_seller_accounts_can_be_deleted()
    {
        if (! Features::hasAccountDeletionFeatures()) {
            return $this->markTestSkipped('Account deletion is not enabled.');
        }

        $this->actingAs($seller = Seller::factory()->create(), $this->guard);

        $response = $this->delete('/user', [
            'password' => 'password',
        ]);

        $this->assertNull($seller->fresh());
    }

    public function test_correct_password_must_be_provided_before_account_can_be_deleted()
    {
        if (! Features::hasAccountDeletionFeatures()) {
            return $this->markTestSkipped('Account deletion is not enabled.');
        }

        $this->actingAs($seller = Seller::factory()->create(), $this->guard);

        $response = $this->delete('/user', [
            'password' => 'wrong-password',
        ]);

        $this->assertNotNull($seller->fresh());
    }
}

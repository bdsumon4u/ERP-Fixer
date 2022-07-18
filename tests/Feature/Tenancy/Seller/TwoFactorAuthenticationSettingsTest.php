<?php

namespace Tests\Feature\Tenancy\Seller;

use App\Models\Seller;
use Tests\RefreshTenantDatabase as RefreshDatabase;
use Tests\TestCase;

class TwoFactorAuthenticationSettingsTest extends TestCase
{
    use RefreshDatabase;

    protected ?string $guard = 'seller';

    protected bool $tenancy = true;

    public function test_two_factor_authentication_can_be_enabled()
    {
        $this->actingAs($seller = Seller::factory()->create(), $this->guard);

        $this->withSession(['auth.password_confirmed_at' => time()]);

        $response = $this->post('/user/two-factor-authentication');

        $this->assertNotNull($seller->fresh()->two_factor_secret);
        $this->assertCount(8, $seller->fresh()->recoveryCodes());
    }

    public function test_recovery_codes_can_be_regenerated()
    {
        $this->actingAs($seller = Seller::factory()->create(), $this->guard);

        $this->withSession(['auth.password_confirmed_at' => time()]);

        $this->post('/user/two-factor-authentication');
        $this->post('/user/two-factor-recovery-codes');

        $seller = $seller->fresh();

        $this->post('/user/two-factor-recovery-codes');

        $this->assertCount(8, $seller->recoveryCodes());
        $this->assertCount(8, array_diff($seller->recoveryCodes(), $seller->fresh()->recoveryCodes()));
    }

    public function test_two_factor_authentication_can_be_disabled()
    {
        $this->actingAs($seller = Seller::factory()->create(), $this->guard);

        $this->withSession(['auth.password_confirmed_at' => time()]);

        $this->post('/user/two-factor-authentication');

        $this->assertNotNull($seller->fresh()->two_factor_secret);

        $this->delete('/user/two-factor-authentication');

        $this->assertNull($seller->fresh()->two_factor_secret);
    }
}

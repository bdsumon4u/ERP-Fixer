<?php

namespace Tests\Feature\Tenancy\Seller;

use App\Models\Seller;
use Tests\RefreshTenantDatabase as RefreshDatabase;
use Tests\TestCase;

class ProfileInformationTest extends TestCase
{
    use RefreshDatabase;

    protected ?string $guard = 'seller';

    protected bool $tenancy = true;

    public function test_profile_information_can_be_updated()
    {
        $this->actingAs($seller = Seller::factory()->create(), $this->guard);

        $response = $this->put('/user/profile-information', [
            'name' => 'Test Name',
            'email' => 'test@example.com',
        ]);

        $this->assertEquals('Test Name', $seller->fresh()->name);
        $this->assertEquals('test@example.com', $seller->fresh()->email);
    }
}

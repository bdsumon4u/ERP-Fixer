<?php

namespace Tests\Feature\Tenancy\Admin;

use App\Models\Admin;
use Tests\RefreshTenantDatabase as RefreshDatabase;
use Tests\TestCase;

class ProfileInformationTest extends TestCase
{
    use RefreshDatabase;

    protected ?string $guard = 'admin';

    protected bool $tenancy = true;

    public function test_profile_information_can_be_updated()
    {
        $this->actingAs($admin = Admin::factory()->create(), $this->guard);

        $response = $this->put('/user/profile-information', [
            'name' => 'Test Name',
            'email' => 'test@example.com',
        ]);

        $this->assertEquals('Test Name', $admin->fresh()->name);
        $this->assertEquals('test@example.com', $admin->fresh()->email);
    }
}

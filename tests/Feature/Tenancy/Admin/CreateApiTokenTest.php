<?php

namespace Tests\Feature\Tenancy\Admin;

use App\Models\Admin;
use Laravel\Jetstream\Features;
use Tests\RefreshTenantDatabase as RefreshDatabase;
use Tests\TestCase;

class CreateApiTokenTest extends TestCase
{
    use RefreshDatabase;

    protected ?string $guard = 'admin';

    protected bool $tenancy = true;

    public function test_api_tokens_can_be_created()
    {
        if (! Features::hasApiFeatures()) {
            return $this->markTestSkipped('API support is not enabled.');
        }

        $this->actingAs($admin = Admin::factory()->create(), $this->guard);

        $response = $this->post('/user/api-tokens', [
            'name' => 'Test Token',
            'permissions' => [
                'read',
                'update',
            ],
        ]);

        $this->assertCount(1, $admin->fresh()->tokens);
        $this->assertEquals('Test Token', $admin->fresh()->tokens->first()->name);
        $this->assertTrue($admin->fresh()->tokens->first()->can('read'));
        $this->assertFalse($admin->fresh()->tokens->first()->can('delete'));
    }
}

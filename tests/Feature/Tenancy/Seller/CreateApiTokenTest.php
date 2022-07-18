<?php

namespace Tests\Feature\Tenancy\Seller;

use App\Models\Seller;
use Laravel\Jetstream\Features;
use Tests\RefreshTenantDatabase as RefreshDatabase;
use Tests\TestCase;

class CreateApiTokenTest extends TestCase
{
    use RefreshDatabase;

    protected ?string $guard = 'seller';

    protected bool $tenancy = true;

    public function test_api_tokens_can_be_created()
    {
        if (! Features::hasApiFeatures()) {
            return $this->markTestSkipped('API support is not enabled.');
        }

        $this->actingAs($seller = Seller::factory()->create(), $this->guard);

        $response = $this->post('/user/api-tokens', [
            'name' => 'Test Token',
            'permissions' => [
                'read',
                'update',
            ],
        ]);

        $this->assertCount(1, $seller->fresh()->tokens);
        $this->assertEquals('Test Token', $seller->fresh()->tokens->first()->name);
        $this->assertTrue($seller->fresh()->tokens->first()->can('read'));
        $this->assertFalse($seller->fresh()->tokens->first()->can('delete'));
    }
}

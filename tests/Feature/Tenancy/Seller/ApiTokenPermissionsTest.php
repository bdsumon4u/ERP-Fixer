<?php

namespace Tests\Feature\Tenancy\Seller;

use App\Models\Seller;
use Illuminate\Support\Str;
use Laravel\Jetstream\Features;
use Tests\RefreshTenantDatabase as RefreshDatabase;
use Tests\TestCase;

class ApiTokenPermissionsTest extends TestCase
{
    use RefreshDatabase;

    protected ?string $guard = 'seller';

    protected bool $tenancy = true;

    public function test_api_token_permissions_can_be_updated()
    {
        if (! Features::hasApiFeatures()) {
            return $this->markTestSkipped('API support is not enabled.');
        }

        $this->actingAs($seller = Seller::factory()->create(), $this->guard);

        $token = $seller->tokens()->create([
            'name' => 'Test Token',
            'token' => Str::random(40),
            'abilities' => ['create', 'read'],
        ]);

        $response = $this->put('/user/api-tokens/'.$token->id, [
            'name' => $token->name,
            'permissions' => [
                'delete',
                'missing-permission',
            ],
        ]);

        $this->assertTrue($seller->fresh()->tokens->first()->can('delete'));
        $this->assertFalse($seller->fresh()->tokens->first()->can('read'));
        $this->assertFalse($seller->fresh()->tokens->first()->can('missing-permission'));
    }
}

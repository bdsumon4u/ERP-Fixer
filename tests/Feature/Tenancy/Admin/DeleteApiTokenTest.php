<?php

namespace Tests\Feature\Tenancy\Admin;

use App\Models\Admin;
use Illuminate\Support\Str;
use Laravel\Jetstream\Features;
use Tests\RefreshTenantDatabase as RefreshDatabase;
use Tests\TestCase;

class DeleteApiTokenTest extends TestCase
{
    use RefreshDatabase;

    protected ?string $guard = 'admin';

    protected bool $tenancy = true;

    public function test_api_tokens_can_be_deleted()
    {
        if (! Features::hasApiFeatures()) {
            return $this->markTestSkipped('API support is not enabled.');
        }

        $this->actingAs($admin = Admin::factory()->create(), $this->guard);

        $token = $admin->tokens()->create([
            'name' => 'Test Token',
            'token' => Str::random(40),
            'abilities' => ['create', 'read'],
        ]);

        $response = $this->delete('/user/api-tokens/'.$token->id);

        $this->assertCount(0, $admin->fresh()->tokens);
    }
}

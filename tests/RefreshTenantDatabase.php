<?php

namespace Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;

trait RefreshTenantDatabase
{
    use RefreshDatabase;

    /**
     * The database connections that should have transactions.
     *
     * `null` is the default landlord connection
     * `tenant` is the tenant connection
     */
    protected array $connectionsToTransact = [
        null, /* 'tenant' */
    ];

    /**
     * Perform any work that should take place once the database has finished refreshing.
     *
     * @return void
     *
     * @throws \Stancl\Tenancy\Exceptions\TenantCouldNotBeIdentifiedById
     */
    protected function afterRefreshingDatabase(): void
    {
        $this->initializeTenant();
    }
}

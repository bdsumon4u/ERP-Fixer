<?php

namespace Tests;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\ParallelTesting;
use Illuminate\Support\Facades\URL;
use Stancl\Tenancy\Facades\Tenancy;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    public Model|null $tenant = null;

    protected bool $tenancy = false;

    protected ?string $guard = null;

    public string $domain = '';

    protected function setUp(): void
    {
        parent::setUp();
        $this->setDomain();
    }

    /**
     * @throws \Stancl\Tenancy\Exceptions\TenantCouldNotBeIdentifiedById
     */
    protected function initializeTenant(): ?Model
    {
        $this->tenant = Tenancy::model()::firstOr(function () {
            return $this->createTenant('test', 'test');
        });

        tenancy()->initialize($this->tenant);
        $this->setDomain();

        return $this->tenant;
    }

    /**
     * @param  string  $id
     * @param  string  $domain
     * @return Model
     */
    protected function createTenant(string $id, string $domain): Model
    {
        if ($token = ParallelTesting::token()) {
            config(['tenancy.database.prefix' => config('tenancy.database.prefix')."{$token}_"]);
        }

        $database = config('tenancy.database.prefix').$id.config('tenancy.database.suffix');

        DB::unprepared("DROP DATABASE IF EXISTS {$database};");
        $tenant = Tenancy::model()->create(compact('id'));

        if (! $tenant->domains()->count()) {
            $tenant->domains()->create(compact('domain'));
        }

        return $tenant;
    }

    protected function setDomain(): void
    {
        $concat = $this->guard ? '.' : '';
        $tenant = $this->tenant ? "{$concat}test" : '';

        $scheme = parse_url(config('app.url'), PHP_URL_SCHEME).'://';
        $this->domain = $scheme.guard_url($this->guard.$tenant);

        URL::formatHostUsing(fn () => $this->domain);
    }

    /**
     * @return void
     */
    protected function tearDown(): void
    {
        if ($this->tenant) {
            tenancy()->model()->all()->each->delete();
        }

        parent::tearDown();
    }
}

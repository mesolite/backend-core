<?php

namespace Mesolite\Tests;

abstract class BaseTest extends \Orchestra\Testbench\TestCase
{
    /**
     * Setup the test environment.
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->artisan('migrate:fresh --force');
        $this->artisan('mesolite:install --force');
    }

    protected function getPackageProviders($app)
    {
        return [
            \Mesolite\MesoliteServiceProvider::class
        ];
    }
}

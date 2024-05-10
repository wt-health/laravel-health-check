<?php

declare(strict_types=1);

namespace Webtools\LaravelHealthCheck\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use Spatie\Health\HealthServiceProvider;
use Webtools\LaravelHealthCheck\LaravelHealthCheckServiceProvider;

class TestCase extends Orchestra
{
    protected function getPackageProviders($app): array
    {
        return [HealthServiceProvider::class, LaravelHealthCheckServiceProvider::class];
    }
}

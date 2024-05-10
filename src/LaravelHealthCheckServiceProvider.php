<?php

declare(strict_types=1);

namespace Webtools\LaravelHealthCheck;

use Illuminate\Support\Facades\Config;
use Spatie\Health\Facades\Health;
use Spatie\Health\ResultStores\InMemoryHealthResultStore;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Webtools\LaravelHealthCheck\Checks\VersionCheck;

class LaravelHealthCheckServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('health-check')
            ->hasConfigFile()
            ->hasRoute('web');
    }

    public function packageRegistered(): void
    {
        Config::set('health.result_stores', [InMemoryHealthResultStore::class]);
        Health::checks([
            VersionCheck::new(),
        ]);
    }
}

<?php

declare(strict_types=1);

namespace Webtools\LaravelHealthCheck\Tests;

use Composer\Composer;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Orchestra\Testbench\Attributes\DefineEnvironment;
use PHPUnit\Framework\Attributes\Test;
use TiMacDonald\Log\LogEntry;
use TiMacDonald\Log\LogFake;

class LaravelHealthCheckTest extends TestCase
{
    protected function defineEnv($app): void
    {
        $app['config']->set('health-check.route.enabled', true);
        $app['config']->set('health-check.key', 'test-key');
        $app['config']->set('health-check.packages', 'composer/composer');
        $app['config']->set('logging.default', 'stderr');
    }

    #[Test]
    #[DefineEnvironment('defineEnv')]
    public function test_route_works(): void
    {
        $this->get('/health-check?key=test-key')->assertOk();
    }

    #[Test]
    #[DefineEnvironment('defineEnv')]
    public function test_route_returns_403_when_no_key_is_used(): void
    {
        $this->get('/health-check')->assertForbidden();
    }

    public function test_route_returns_404_when_disabled(): void
    {
        $this->get('/health-check')->assertNotFound();
    }

    #[Test]
    #[DefineEnvironment('defineEnv')]
    public function test_version_check_artisan(): void
    {
        LogFake::bind();
        Artisan::call('health:check');

        Log::assertLogged(fn (LogEntry $log) => $log->level === 'info'
            && $log->message === 'health'
            && $log->context === [
                'health' => [
                    'php' => phpversion(),
                    'laravel' => app()->version(),
                    'mysql' => '8.0.0',
                    'packages' => [str_replace('/', '-', 'composer/composer') => Composer::getVersion()],
                ]]
        );
    }
}

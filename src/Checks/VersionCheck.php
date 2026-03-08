<?php

declare(strict_types=1);

namespace Webtools\LaravelHealthCheck\Checks;

use Composer\InstalledVersions;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Spatie\Health\Checks\Check;
use Spatie\Health\Checks\Result;

class VersionCheck extends Check
{
    public function run(): Result
    {
        $result = Result::make();

        $meta = $this->getResultMeta();
        $result->meta($meta);
        Log::withContext($meta)->info('health');

        return $result->ok();
    }

    /**
     * @return array<string,array{php: string, laravel: string, mysql: string, packages: array<string,string>, node: string}>
     */
    private function getResultMeta(): array
    {
        return [
            'health' => [
                'php' => phpversion(),
                'laravel' => app()->version(),
                'mysql' => $this->getMysqlVersion(),
                'packages' => $this->getPackagesVersions(),
                'node' => $this->getNodeVersion(),
            ],
        ];
    }

    private function getMysqlVersion(): string
    {
        return app()->runningUnitTests() ? '8.0.0' : DB::select('select version()')[0]->{'version()'};
    }

    private function getNodeVersion(): string
    {
        $fileContents = File::get(base_path('package.json'));
        if (Str::length($fileContents) === 0) {
            return 'N/A';
        }
        $data = json_decode($fileContents, true);

        return Arr::exists($data, 'engines') && Arr::exists($data['engines'], 'node') ? preg_replace('/[>=]/', '', $data['engines']['node']) : 'N/A';
    }

    /**
     * @return array<string,string>
     */
    private function getPackagesVersions(): array
    {
        /** @var array<string> $packages */
        $packages = config('health-check.packages');

        return collect($packages)->mapWithKeys(function (string $package) {
            return [str_replace('/', '-', $package) => InstalledVersions::getPrettyVersion($package)];
        })->toArray();
    }
}

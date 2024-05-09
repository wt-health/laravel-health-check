<?php

declare(strict_types=1);

namespace Webtools\LaravelHealthCheck\Checks;

use Composer\InstalledVersions;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
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

    private function getResultMeta(): array
    {
        $mysql = DB::select('select version()')[0]->{'version()'};

        return [
            'health' => [
                'php' => phpversion(),
                'laravel' => app()->version(),
                'mysql' => $mysql,
                'packages' => $this->getPackagesVersions(),
            ],
        ];
    }

    private function getPackagesVersions(): array
    {
        return collect(config('health-check.packages'))->mapWithKeys(function (string $package) {
            return [$package => InstalledVersions::getPrettyVersion($package)];
        })->toArray();
    }
}

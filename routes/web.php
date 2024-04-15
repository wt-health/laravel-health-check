<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Spatie\Health\Http\Controllers\HealthCheckJsonResultsController;
use Webtools\LaravelHealthCheck\Http\Middleware\ApiKey;

Route::middleware([ApiKey::class])->get('health-check', HealthCheckJsonResultsController::class);

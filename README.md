Laravel Health Check
================================

[![Test CI](https://github.com/wt-health/laravel-health-check/actions/workflows/test.yml/badge.svg)](https://github.com/wt-health/laravel-health-check/actions/workflows/test.yml)

Adds custom version check based on [spatie/laravel-health](https://github.com/spatie/laravel-health) 

Installation & Configuration
--------------

```bash
 composer require wthealth/laravel-health-check
```

To have logs and metrics logged in Datadog, you must schedule health check command on your setup.


serverless.yaml
```yaml
    artisan:
        handler: artisan
        runtime: php-83-console
        timeout: 720 # in seconds
        layers:
            - ${bref-extra:gd-php-83}
            - ${bref-extra:redis-php-83}
        events:
            - schedule:
                  rate: rate(12 hours)
                  input: '"health:check"'
```



Usage
------

You may configure these environment variable below accordingly
```dotenv
HEALTH_API_ROUTE_ENABLED= # enable /health-check endpoint - default: false
HEALTH_API_KEY= # used to secure /health-check endpoint
HEALTH_API_PACKAGES= # packages ( comma separated ) to have its versions checked
```

You can health check either accessing `/health-check` endpoint or running `php artisan health:list --fresh`

A schedule job runs every day and output logs that are ingested by Datadog 


License
-------
The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

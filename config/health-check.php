<?php

declare(strict_types=1);

return [
    /*
     * Key to be able to read endpoint
     */
    'key' => env('HEALTH_API_KEY'),

    /*
     * Packages to have versions checked against, comma limited (,)
     */
    'packages' => explode(',', env('HEALTH_API_PACKAGES', 'composer/composer')),

];

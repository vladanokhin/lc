<?php

namespace App\Services;

class BinomApiVersion
{

    /**
     * Api version of binom
     *
     * @var array|string[]
     */
    private static array $versions = [
        'v1',
        'v2',
    ];

    /**
     * Get the api version of binom
     *
     * @return array|string[]
     */
    public static function getVersions(): array
    {
        return self::$versions;
    }
}

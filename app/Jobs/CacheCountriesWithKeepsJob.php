<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Models\Keep;
use Illuminate\Contracts\Cache\Repository as CacheRepository;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class CacheCountriesWithKeepsJob implements ShouldQueue
{
    use Queueable;

    public const string CACHE_KEY = 'countries-with-keeps';

    public function handle(CacheRepository $cache): void
    {
        $cache->rememberForever(self::CACHE_KEY, fn () => Keep::query()
            ->select('country')
            ->distinct()
            ->orderBy('country')
            ->pluck('country')
            ->toArray()
        );
    }
}

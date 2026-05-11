<?php

declare(strict_types=1);

namespace App\Jobs;

use App\DataObjects\KeepEntry;
use App\Enums\Country;
use App\Models\Keep;
use Illuminate\Contracts\Cache\Repository as CacheRepository;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Http\Client\Factory as HttpFactory;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use RuntimeException;

class SynchroniseJob implements ShouldQueue
{
    use Queueable;

    private const BASE_URL = 'https://keep-data.voke.dev';

    public function handle(CacheRepository $cache, HttpFactory $http): void
    {
        $response = $http
            ->get($this->buildUrl('/data/.meta.json'))
            ->throw()
            ->json();

        if (! is_array($response) || ! Arr::has($response, ['countries', 'hashes'])) {
            throw new RuntimeException('Invalid response from the API.');
        }

        /** @var array<value-of<Country>, string> $hashes */
        $hashes = Arr::get($response, 'hashes', []);

        /** @var list<value-of<Country>> $countries */
        $countries = Arr::get($response, 'countries', []);

        foreach ($countries as $country) {
            $country = Country::from($country);

            $hash = Arr::get($hashes, sprintf('%s.json', strtolower($country->value)));

            if ($hash === null) {
                throw new RuntimeException("The specified country ({$country->value}) does not have a registered cache.");
            }

            $cacheKey = sprintf('hashes::countries::%s', $country->value);

            if ($cache->get($cacheKey) === $hash) {
                continue;
            }

            /** @var list<array<string, mixed>> $response */
            $response = $http
                ->get($this->buildUrl(sprintf('/data/%s.json', strtolower($country->value))))
                ->throw()
                ->json();

            KeepEntry::collect($response, Collection::class)
                ->map(fn (KeepEntry $keep) => Keep::query()
                    ->updateOrCreate(
                        ['uuid' => $keep->id],
                        Arr::except($keep->toArray(), ['id', 'coordinates']) // @phpstan-ignore argument.type
                    )
                );

            $cache->forever($cacheKey, $hash);
        }
    }

    private function buildUrl(string $string): string
    {
        return self::BASE_URL.$string;
    }
}

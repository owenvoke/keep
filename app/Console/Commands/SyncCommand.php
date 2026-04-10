<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\DataObjects\KeepEntry;
use App\Models\Keep;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Contracts\Cache\Repository as CacheRepository;
use Illuminate\Http\Client\Factory as HttpFactory;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

#[Signature('app:sync')]
#[Description('Synchronise Keep data from the external API.')]
class SyncCommand extends Command
{
    public function handle(HttpFactory $http, CacheRepository $cache): void
    {
        $response = $http
            ->get('https://keep-data.voke.dev/data/gb.json')
            ->throw()
            ->json();

        KeepEntry::collect($response, Collection::class)
            ->map(fn (KeepEntry $keep) => Keep::query()->updateOrCreate(['uuid' => $keep->id], Arr::except($keep->toArray(), 'id')));

        $this->info('Keep data has been synced.');
    }
}

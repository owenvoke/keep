<?php

declare(strict_types=1);

namespace App\Jobs;

use App\DataObjects\KeepEntry;
use App\Models\Keep;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Http\Client\Factory as HttpFactory;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

class SynchroniseJob implements ShouldQueue
{
    use Queueable;

    public function __construct() {}

    public function handle(HttpFactory $http): void
    {
        $response = $http
            ->get('https://keep-data.voke.dev/data/all.json')
            ->throw()
            ->json();

        KeepEntry::collect($response, Collection::class)
            ->map(fn (KeepEntry $keep) => Keep::query()->updateOrCreate(['uuid' => $keep->id],
                Arr::except($keep->toArray(), 'id')));
    }
}

<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Jobs\CacheCountriesWithKeepsJob;
use App\Jobs\SynchroniseJob;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('app:sync')]
#[Description('Synchronise Keep data from the external API.')]
class SyncCommand extends Command
{
    public function handle(): void
    {
        SynchroniseJob::dispatchSync();

        CacheCountriesWithKeepsJob::dispatchSync();

        $this->components->info('Keep data has been synced.');
    }
}

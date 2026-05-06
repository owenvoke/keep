<?php

declare(strict_types=1);

use App\Models\Keep;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('keeps', function (Blueprint $table) {
            $table->float('latitude', precision: 5)->after('coordinates')->nullable();
            $table->float('longitude', precision: 5)->after('latitude')->nullable();
        });

        Keep::withoutTimestamps(function () {
            DB::transaction(function () {
                Keep::query()
                    ->chunkById(1000, function (Collection $keeps) {
                        /** @var Collection<int, Keep> $keeps */
                        foreach ($keeps as $keep) {
                            /** @var string $json */
                            $json = $keep->getRawOriginal('coordinates');

                            /** @var array{latitude: float, longitude: float} $coordinates */
                            $coordinates = json_decode($json, true, flags: JSON_THROW_ON_ERROR);

                            $keep->update([
                                'latitude' => Arr::get($coordinates, 'latitude'),
                                'longitude' => Arr::get($coordinates, 'longitude'),
                            ]);
                        }
                    });
            });
        });

        Schema::table('keeps', function (Blueprint $table) {
            $table->dropColumn('coordinates');

            $table->float('latitude', precision: 5)->after('coordinates')->change();
            $table->float('longitude', precision: 5)->after('latitude')->change();
        });
    }
};

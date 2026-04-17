<?php

declare(strict_types=1);

namespace App\Models;

use App\DataObjects\Coordinates;
use App\Enums\Country;
use App\Enums\Region;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\AsUri;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;
use Illuminate\Support\Uri;

/**
 * @property string $uuid
 * @property string $name
 * @property Country $country
 * @property Region|null $region
 * @property Coordinates $coordinates
 * @property string $built
 * @property string $condition
 * @property string $owned_by
 * @property string $type
 * @property bool $accessible
 * @property Collection<int, string>|null $alternative_names
 * @property string|null $description
 * @property Uri|null $homepage
 * @property CarbonImmutable|null $created_at
 * @property CarbonImmutable|null $updated_at
 * @property-read Collection<int, Visit> $visits
 *
 * @method Builder<self> nearestTo(Coordinates $coordinates, int $distance = 25, int $limit = 50, bool $includeZero = false)
 */
class Keep extends Model
{
    use HasUuids;

    /** {@inheritdoc} */
    protected $guarded = [];

    /** {@inheritdoc} */
    public $incrementing = false;

    /** {@inheritdoc} */
    protected $primaryKey = 'uuid';

    /** {@inheritdoc} */
    protected function casts(): array
    {
        return [
            'accessible' => 'boolean',
            'alternative_names' => 'collection',
            'coordinates' => Coordinates::class,
            'country' => Country::class,
            'homepage' => AsUri::class,
            'region' => Region::class,
        ];
    }

    /** @return HasMany<Visit, $this> */
    public function visits(): HasMany
    {
        return $this->hasMany(Visit::class);
    }

    /**
     * @param  Builder<self>  $query
     * @return Builder<self>
     */
    public function scopeNearestTo(
        Builder $query,
        Coordinates $coordinates,
        int $distance = 25,
        bool $includeZero = false
    ): Builder {
        return $query
            ->select()
            ->selectRaw("ROUND(
                ? * ACOS(
                    COS(RADIANS(?))
                    * COS(RADIANS(JSON_EXTRACT(coordinates, '$.latitude')))
                    * COS(RADIANS(JSON_EXTRACT(coordinates, '$.longitude')) - RADIANS(?))
                    + SIN(RADIANS(?))
                    * SIN(RADIANS(JSON_EXTRACT(coordinates, '$.latitude')))
                ), 2
            ) AS distance", [
                6371, // Radius of Earth in kilometers
                $coordinates->latitude,
                $coordinates->longitude,
                $coordinates->latitude,
            ])
            ->when($includeZero === false, fn (Builder $query) => $query->where('distance', '!=', 0))
            ->groupBy('distance')
            ->having('distance', '<=', $distance)
            ->orderBy('distance');
    }

    public function visitedBy(User $user): bool
    {
        return $this->visits()->where('user_id', $user->id)->exists();
    }

    /** @return array<string, mixed> */
    public function toJsonMarker(): array
    {
        $user = auth()->user();

        assert($user !== null);

        return [
            'longitude' => $this->coordinates->longitude,
            'latitude' => $this->coordinates->latitude,
            'name' => $this->name,
            'built' => $this->built,
            'type' => $this->type,
            'condition' => $this->condition,
            'url' => route('keep.show', ['keep' => $this]),
            'color' => $this->visitedBy($user) ? 'green' : '#bbb',
        ];
    }
}

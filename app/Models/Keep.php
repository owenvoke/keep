<?php

declare(strict_types=1);

namespace App\Models;

use App\DataObjects\Coordinates;
use App\Enums\Region;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Casts\AsUri;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;
use Illuminate\Support\Uri;

/**
 * @property string $uuid
 * @property string $name
 * @property Region $region
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
            'region' => Region::class,
            'coordinates' => Coordinates::class,
            'homepage' => AsUri::class,
        ];
    }

    /** @return HasMany<Visit, $this> */
    public function visits(): HasMany
    {
        return $this->hasMany(Visit::class);
    }
}

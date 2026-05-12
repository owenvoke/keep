<?php

declare(strict_types=1);

namespace App\Models;

use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property string $uuid
 * @property string $name
 * @property string $description
 * @property int $user_id
 * @property bool $is_public
 * @property CarbonImmutable|null $created_at
 * @property CarbonImmutable|null $updated_at
 * @property-read User $user
 * @property-read EloquentCollection<array-key, Keep> $keeps
 */
class Collection extends Model
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
            'is_public' => 'bool',
            'user_id' => 'int',
        ];
    }

    /** @return BelongsTo<User, $this> */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /** @return BelongsToMany<Keep, $this> */
    public function keeps(): BelongsToMany
    {
        return $this->belongsToMany(Keep::class)->withTimestamps();
    }
}

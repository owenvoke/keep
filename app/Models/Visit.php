<?php

declare(strict_types=1);

namespace App\Models;

use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property string $uuid
 * @property string $keep_uuid
 * @property int $user_id
 * @property string $comment
 * @property CarbonImmutable $visited_at
 * @property CarbonImmutable|null $created_at
 * @property CarbonImmutable|null $updated_at
 * @property-read User $user
 * @property-read Keep $keep
 */
class Visit extends Model
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
            'user_id' => 'int',
            'visited_at' => 'datetime',
        ];
    }

    /** @return BelongsTo<Keep, $this> */
    public function keep(): BelongsTo
    {
        return $this->belongsTo(Keep::class);
    }

    /** @return BelongsTo<User, $this> */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

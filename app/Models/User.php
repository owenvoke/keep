<?php

declare(strict_types=1);

namespace App\Models;

use App\DataObjects\Coordinates;
use App\DataObjects\Settings;
use App\Enums\Country;
use Carbon\CarbonImmutable;
use Database\Factories\UserFactory;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Fortify\Features;
use Laravel\Fortify\TwoFactorAuthenticatable;

/**
 * @property string $name
 * @property string $email
 * @property string $password
 * @property Country|null $country
 * @property Coordinates|null $home_coordinates
 * @property Settings $settings
 * @property CarbonImmutable|null $email_verified_at
 * @property CarbonImmutable|null $created_at
 * @property CarbonImmutable|null $updated_at
 */
#[Fillable(['name', 'email', 'password', 'country', 'home_coordinates', 'settings'])]
#[Hidden(['password', 'two_factor_secret', 'two_factor_recovery_codes', 'remember_token'])]
class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, TwoFactorAuthenticatable;

    /** {@inheritdoc} */
    protected function casts(): array
    {
        return [
            'country' => Country::class,
            'email_verified_at' => 'datetime',
            'home_coordinates' => Coordinates::class,
            'password' => 'hashed',
            'settings' => Settings::class.':default',
        ];
    }

    public function initials(): string
    {
        return Str::of($this->name)
            ->explode(' ')
            ->take(2)
            ->map(fn ($word) => Str::substr($word, 0, 1))
            ->implode('');
    }

    /** @return HasMany<Visit, $this> */
    public function visits(): HasMany
    {
        return $this->hasMany(Visit::class);
    }

    public function hasVisited(Keep $keep): bool
    {
        return $this->visits()->where('keep_uuid', $keep->uuid)->exists();
    }

    /** {@inheritdoc} */
    public function hasVerifiedEmail(): bool
    {
        if (! Features::enabled(Features::emailVerification())) {
            return true;
        }

        return parent::hasVerifiedEmail();
    }
}

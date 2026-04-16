<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Routing\Redirector;
use Laravel\Socialite\Contracts\Factory as SocialiteFactory;
use Symfony\Component\HttpFoundation\RedirectResponse;

readonly class OIDCController
{
    private const string PROVIDER = 'oidc';

    public function __construct(
        private SocialiteFactory $socialite,
        private Redirector $redirect
    ) {}

    public function redirect(): RedirectResponse
    {
        return $this->socialite->driver(self::PROVIDER)->redirect();
    }

    public function callback(StatefulGuard $guard): RedirectResponse
    {
        $socialUser = $this->socialite->driver(self::PROVIDER)->user();

        $user = User::query()->firstOrCreate(['email' => $socialUser->getEmail()], [
            'name' => $socialUser->getName(),
        ]);

        $guard->login($user);

        return $this->redirect->route('keep.index');
    }
}

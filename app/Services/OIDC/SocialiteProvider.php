<?php

declare(strict_types=1);

namespace App\Services\OIDC;

use App\Services\OIDC\Exceptions\OIDCConfigurationException;
use Exception;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\RequestOptions;
use Illuminate\Support\Arr;
use SocialiteProviders\Manager\OAuth2\AbstractProvider;
use SocialiteProviders\Manager\OAuth2\User as SocialiteUser;

class SocialiteProvider extends AbstractProvider
{
    public const string IDENTIFIER = 'OIDC';

    /** @var array<string, mixed>|null */
    public array|null $configuration = null;

    /** @var list<string> */
    protected $scopes = ['openid', 'profile', 'email'];

    /** {@inheritdoc} */
    protected $scopeSeparator = ' ';

    /** {@inheritdoc} */
    protected function usesPKCE(): bool
    {
        return $this->getConfig('use_pkce') === true;
    }

    /** @return list<string> */
    public static function additionalConfigKeys(): array
    {
        return ['base_url', 'use_pkce'];
    }

    protected function getBaseUrl(): string
    {
        $baseurl = $this->getConfig('base_url');

        if ($baseurl === null) {
            throw new OIDCConfigurationException('Missing base_url');
        }

        return rtrim($baseurl, '/');
    }

    /** {@inheritdoc} */
    protected function getTokenUrl(): string
    {
        return $this->getOpenIdConfig('token_endpoint');
    }

    /** {@inheritdoc} */
    protected function getAuthUrl($state): string
    {
        return $this->buildAuthUrlFromBase(
            $this->getOpenIdConfig('authorization_endpoint'),
            $state
        );
    }

    /** @throws GuzzleException */
    protected function getOpenIdConfig(string|null $key): mixed
    {
        if ($this->configuration === null) {
            $configUrl = "{$this->getBaseUrl()}/.well-known/openid-configuration";

            try {
                $response = $this->getHttpClient()->get($configUrl);

                $this->configuration = json_decode((string) $response->getBody(), true, 512, JSON_THROW_ON_ERROR);
            } catch (Exception $e) {
                throw new OIDCConfigurationException("Unable to get the OIDC configuration from {$configUrl}: {$e->getMessage()}");
            }
        }

        assert(is_array($this->configuration));

        return Arr::get($this->configuration, $key);
    }

    /** {@inheritdoc} */
    protected function getUserByToken($token)
    {
        $endpoint = $this->getOpenIdConfig('userinfo_endpoint');

        assert(is_string($endpoint));

        $response = $this->getHttpClient()->get($endpoint, [
            RequestOptions::HEADERS => [
                'Accept' => 'application/json',
                'Authorization' => "Bearer {$token}",
            ],
        ]);

        return json_decode((string) $response->getBody(), true);
    }

    /** @param array<string, mixed> $user */
    protected function mapUserToObject(array $user): SocialiteUser
    {
        return new SocialiteUser()->setRaw($user)->map(
            [
                'id' => $user['sub'],
                'email' => $user['email'] ?? null,
                'name' => $user['name'] ?? null,
            ]
        );
    }
}

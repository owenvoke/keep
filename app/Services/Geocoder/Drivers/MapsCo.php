<?php

declare(strict_types=1);

namespace App\Services\Geocoder\Drivers;

use Geocoder\Collection;
use Geocoder\Exception\InvalidCredentials;
use Geocoder\Exception\InvalidServerResponse;
use Geocoder\Exception\UnsupportedOperation;
use Geocoder\Http\Provider\AbstractHttpProvider;
use Geocoder\Model\AddressBuilder;
use Geocoder\Model\AddressCollection;
use Geocoder\Provider\Provider;
use Geocoder\Query\GeocodeQuery;
use Geocoder\Query\ReverseQuery;
use Psr\Http\Client\ClientInterface;
use SensitiveParameter;

class MapsCo extends AbstractHttpProvider implements Provider
{
    public const string GEOCODE_ENDPOINT_URL_SSL = 'https://geocode.maps.co/search?q=%s';

    public function __construct(
        ClientInterface $client,
        #[SensitiveParameter]
        private readonly string|null $apiKey,
    ) {
        parent::__construct($client);
    }

    public function reverseQuery(ReverseQuery $query): Collection
    {
        throw new UnsupportedOperation('The Maps.co provider is not able to do reverse geocoding.');
    }

    public function geocodeQuery(GeocodeQuery $query): Collection
    {
        $address = $query->getText();

        // This API doesn't handle IPs
        if (filter_var($address, FILTER_VALIDATE_IP)) {
            throw new UnsupportedOperation('The Maps.co provider does not support IP addresses.');
        }

        return $this->fetchUrl(
            sprintf(self::GEOCODE_ENDPOINT_URL_SSL, rawurlencode($address)),
            $query->getLimit()
        );
    }

    public function getName(): string
    {
        return 'maps_co';
    }

    private function fetchUrl(string $url, int $limit = 1): AddressCollection
    {
        if ($this->apiKey === null) {
            throw new InvalidCredentials('You must provide an API key.');
        }

        $request = $this
            ->getRequest($url)
            ->withHeader('Authorization', "Bearer {$this->apiKey}");

        $content = $this->getParsedResponse($request);
        $json = $this->validateResponse($url, $content);

        // No result
        if (! count($json)) {
            return new AddressCollection([]);
        }

        $results = [];
        foreach ($json as $result) {
            $builder = new AddressBuilder($this->getName());

            if (isset($result->place_id)) {
                $builder->setValue('id', $result->place_id);
            }

            $builder->setCoordinates($result->lat, $result->lon);

            foreach ($result->address as $type => $component) {
                $this->updateAddressComponent($builder, $type, $component);
            }

            $results[] = $builder->build();

            if (count($results) >= $limit) {
                break;
            }
        }

        return new AddressCollection($results);
    }

    private function validateResponse(string $url, string $content): array
    {
        $json = json_decode($content, true, flags: JSON_THROW_ON_ERROR);

        // API error
        if (! is_array($json)) {
            throw InvalidServerResponse::create($url);
        }

        return $json;
    }

    private function updateAddressComponent(AddressBuilder $builder, string $type, mixed $value): void
    {
        switch ($type) {
            case 'house_number':
                $builder->setStreetNumber($value);

                break;

            case 'road':
                $builder->setStreetName($value);

                break;

            case 'postcode':
                $builder->setPostalCode($value);

                break;

            case 'suburb':
                $builder->setSubLocality($value);

                break;

            case 'city':
            case 'town':
                $builder->setLocality($value);

                break;

            case 'country':
                $builder->setCountry($value);

                break;

            case 'country_code':
                $builder->setCountryCode(strtoupper($value));

                break;

            default:
        }
    }
}

<?php


namespace Gbowo\Adapter\Amplifypay\Plugin;

use function GuzzleHttp\json_decode;
use Gbowo\Plugin\AbstractFetchAllPlans;
use Psr\Http\Message\ResponseInterface;
use Gbowo\Exception\InvalidHttpResponseException;

/**
 * @author Lanre Adelowo <me@adelowolanre.com>
 * Class FetchAllPlans
 * @package Gbowo\Adapter\Amplifypay\Plugin
 */
class FetchAllPlans extends AbstractFetchAllPlans
{

    const ALL_PLANS_RELATIVE_LINK = "/plan?merchantId=:m&apiKey=:key";

    /**
     * @var string
     */
    protected $baseUrl;
    /**
     * @var array
     */
    protected $apiKeys;

    public function __construct(string $baseUrl, array $apiKeys)
    {
        $this->apiKeys = $apiKeys;
        $this->baseUrl = $baseUrl;
    }

    /**
     * @return mixed
     * @throws \Gbowo\Exception\InvalidHttpResponseException if the response status code is not 200
     */
    public function handle()
    {

        $link = $this->baseUrl . str_replace(":m", $this->apiKeys['merchantId'], self::ALL_PLANS_RELATIVE_LINK);

        $link = str_replace(":key", $this->apiKeys['apiKey'], $link);

        /**
         * @var ResponseInterface $response
         */
        $response = $this->adapter->getHttpClient()
            ->get($link);

        if (200 !== $response->getStatusCode()) {
            throw new InvalidHttpResponseException(
                "Expected 200. Got{$response->getStatusCode()} instead"
            );
        }

        return json_decode($response->getBody(), true);
    }
}

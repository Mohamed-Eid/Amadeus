<?php

namespace Bluex\Amadeus;

use GuzzleHttp\Client;

class Amadeus
{

    public $client;


    protected string $accessToken;
    protected string $accessTokenSecret;
    protected string $bearerToken;
    protected  $consumerKey;
    protected  $consumerSecret;


    public function __construct(
        string $accessToken,
        string $accessTokenSecret,
        string $bearerToken,
        string $consumerKey = null,
        string $consumerSecret = null
    ) {
        $this->accessToken = $accessToken;
        $this->accessTokenSecret = $accessTokenSecret;
        $this->bearerToken = $bearerToken;
        $this->consumerKey = $consumerKey;
        $this->consumerSecret = $consumerSecret;

        $this->client = new Client(['headers' => $this->buildHeaders()]);
    }


    private function buildHeaders()
    {
        return [
            'Authorization' => 'Bearer ' . $this->bearerToken
        ];
    }


}

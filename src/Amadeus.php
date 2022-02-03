<?php

namespace Bluex\Amadeus;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\CurlHandler;
use GuzzleHttp\HandlerStack;
use Psr\Http\Message\RequestInterface;

class Amadeus
{
    public $client;

    protected string $url;
    protected string $clientId;
    protected string $clientSecret;
    protected string $accessToken;
    public string $version;
    public int $expiresIn;

    public array $headers;
    public $stack;

    public function __construct(
        string $clientId,
        string $clientSecret,
        string $version = 'v2',
        bool   $test = true
    ) {
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
        $this->version = $version;
        $this->accessToken = '';
        $this->expiresIn = 0;
        if ($test) {
            $this->url = "https://test.api.amadeus.com/{$this->version}";
        } else {
            $this->url = "https://test.api.amadeus.com/{$this->version}";
        }

        $this->headers = [];

        $this->stack = new HandlerStack();
        $this->stack->setHandler(new CurlHandler());
        $this->stack->push($this->handleAuthorizationHeader());

        $this->client = new Client([
            'handler'  => $this->stack,
            'base_uri' =>  $this->url,
            'headers'  => $this->headers
        ]);

        $this->auth();
    }


    /** 
     * Handle Authorization Header
     */
    private function handleAuthorizationHeader()
    {
        return function (callable $handler) {
            return function (RequestInterface $request, array $options) use ($handler) {
                if ($this->accessToken != '') {
                    $request = $request->withHeader('Authorization', 'Bearer ' . $this->accessToken);
                }

                return $handler($request, $options);
            };
        };
    }

    private function auth(string $grant_type = 'client_credentials')
    {
        $res = $this->client->post("v1/security/oauth2/token", [
            'form_params' => [
                'client_id' => $this->clientId,
                'client_secret' => $this->clientSecret,
                'grant_type' => $grant_type,
            ]
        ]);
        $res  = json_decode($res->getBody());

        $this->expiresIn = $res->expires_in;

        $this->accessToken = $res->access_token;
    }

    public function getTokenInfo()
    {
        $res = $this->client->get("{$this->version}/security/oauth2/token/{$this->accessToken}");
        return json_decode($res->getBody());
    }


    /**
     * [
                'originLocationCode' => '',
                'destinationLocationCode' => '',
                'departureDate' => '',
                'returnDate' => '',
                'adults' => '',
                'children' => '',
                'infants'  => '',
                'includedAirlineCodes' => '',
                'max' => '',
                'travelClass' => '',
            ]
     *
     * @param [type] $params
     * @return void
     */
    public function flightOffers($params)
    {
        $res = $this->client->get("{$this->version}/shopping/flight-offers", [
            'query' => $params
        ]);
        return json_decode($res->getBody());
    }

    public function getLocations($keyword, $subType='CITY,AIRPORT'){
        $res = $this->client->get("v1/reference-data/locations", [
            'query' => [
                'keyword' => $keyword,
                'subType' => $subType
            ]
        ]);
        return json_decode($res->getBody());
    }
}

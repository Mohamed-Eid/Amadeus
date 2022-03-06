<?php

namespace Bluex\Amadeus;

use Bluex\Amadeus\Models\Search\Hotel;
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
    public int $expiresIn;

    public array $headers;
    public $stack;

    public function __construct(
        string $clientId,
        string $clientSecret,
        bool   $test = true
    ) {
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
        $this->accessToken = '';
        $this->expiresIn = 0;
        if ($test) {
            $this->url = "https://test.api.amadeus.com";
        } else {
            $this->url = "https://test.api.amadeus.com";
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
        $res = $this->client->get("v2/security/oauth2/token/{$this->accessToken}");
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
        $res = $this->client->get("v2/shopping/flight-offers", [
            'query' => $params
        ]);
        return json_decode($res->getBody());
    }

    public function advancedFlightOffers($params)
    {
        $res = $this->client->post("v2/shopping/flight-offers", [
            'json' => $params
        ]);
        return json_decode($res->getBody());
    }

    public function flightOfferPrice($offer)
    {
        $res = $this->client->post("v1/shopping/flight-offers/pricing", [
            'json' => [
                'data' => [
                    'type' => 'flight-offers-pricing',
                    'flightOffers' => [$offer]
                ]
            ]
        ]);

        $res  = json_decode($res->getBody());

        return $res;
    }

    public function flightOrder($flightOffer, $travelers, $remarks, $ticketingAgreement, $contacts)
    {
        // dd([
        //     'data' => [
        //         'type'                 => 'flight-order',
        //         'flightOffers'         => [$flightOffer],
        //         'travelers'            => $travelers,
        //         'remarks'              => $remarks,
        //         'ticketingAgreement'   => $ticketingAgreement,
        //         'contacts'             => $contacts,
        //     ]
        // ]);
        $res = $this->client->post("v1/shopping/flight-offers/pricing", [
            'json' => [
                'data' => [
                    'type'                 => 'flight-order',
                    'flightOffers'         => [$flightOffer],
                    'travelers'            => $travelers,
                    'remarks'              => $remarks,
                    'ticketingAgreement'   => $ticketingAgreement,
                    'contacts'             => $contacts,
                ]
            ]
        ]);

        $res  = json_decode($res->getBody());

        return $res;
    }

    public function getLocations($keyword, $subType = 'CITY,AIRPORT')
    {
        $res = $this->client->get("v1/reference-data/locations", [
            'query' => [
                'keyword' => $keyword,
                'subType' => $subType
            ]
        ]);
        return json_decode($res->getBody());
    }

    public function getHotelNames($keyword, $subType = 'HOTEL_LEISURE')
    {
        $res = $this->client->get("v1/reference-data/locations/hotel", [
            'query' => [
                'keyword' => $keyword,
                'subType' => $subType
            ]
        ]);
        return json_decode($res->getBody());
    }

    public function hotels(Hotel $hotel)
    {
        $data = $hotel->getData();
        $res = $this->client->get("v2/shopping/hotel-offers", [
            'query' => $data
        ]);
        return json_decode($res->getBody());
    }

    public function getHotel(string $hotelId)
    {
        $res = $this->client->get("v2/shopping/hotel-offers/by-hotel", [
            'query' => [
                'hotelId' => $hotelId
            ]
        ]);
        return json_decode($res->getBody());
    }

    public function getHotelReputation(string $hotelIds)
    {
        $res = $this->client->get("v2/e-reputation/hotel-sentiments", [
            'query' => [
                'hotelIds' => $hotelIds
            ]
        ]);
        return json_decode($res->getBody());
    }

    public function getHotelOffer(string $offerId)
    {
        // dd("v2/shopping/hotel-offers/$offerId");
        $res = $this->client->get("v2/shopping/hotel-offers/$offerId");
        return json_decode($res->getBody());
    }

    public function bookHotel($offerId, $guests, $payments)
    {
        $res = $this->client->post("v1/booking/hotel-bookings", [
            'json' => [
                'data' => [
                    'offerId'   => $offerId,
                    'guests'    => $guests,
                    'payments'  => $payments,
                ]
            ]
        ]);

        $res  = json_decode($res->getBody());

        return $res;
    }
}

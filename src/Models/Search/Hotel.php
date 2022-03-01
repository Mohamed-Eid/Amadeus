<?php

namespace Bluex\Amadeus\Models\Search;

use Bluex\Amadeus\Contracts\SearchContract;
use Exception;

class Hotel implements SearchContract
{
    // public $cityCode;
    // public $checkInDate = null;
    // public $checkOutDate = null;
    // public $priceRange = null;
    // public $currency = null;
    // public $boardType = null;
    // public $lang = null;
    // public $hotelIds = null;
    // public $roomQuantity = null;
    // public $adults = null;
    // public $radius = null;
    // public $radiusUnit = "KM";
    // public $paymentPolicy = "NONE";
    // public $includeClosed = false;
    // public $bestRateOnly = true;
    // public $view = "FULL";
    // public $sort = "NONE";

    public array $data = [];

    public function getData(): array
    {
        if (!isset($this->data['cityCode'])) {
            throw new Exception("cityCode is required param", 1);
        }
        return $this->data;
    }

    public function cityCode($value): Hotel
    {
        $this->data['cityCode'] = $value;
        return $this;
    }

    public function rating($value): Hotel
    {
        $this->data['rating'] = $value;
        return $this;
    }

    public function checkInDate($value): Hotel
    {
        $this->data['checkInDate'] = $value;
        return $this;
    }

    public function checkOutDate($value): Hotel
    {
        $this->data['checkOutDate'] = $value;
        return $this;
    }

    public function priceRange($value): Hotel
    {
        $this->data['priceRange'] = $value;
        return $this;
    }

    public function currency($value): Hotel
    {
        $this->data['currency'] = $value;
        return $this;
    }

    public function boardType($value): Hotel
    {
        $this->data['boardType'] = $value;
        return $this;
    }

    public function lang($value): Hotel
    {
        $this->data['lang'] = $value;
        return $this;
    }

    public function adults($value): Hotel
    {
        $this->data['adults'] = $value;
        return $this;
    }

    public function children($value): Hotel
    {
        $this->data['children'] = $value;
        return $this;
    }

    public function infants($value): Hotel
    {
        $this->data['infants'] = $value;
        return $this;
    }

    public function lat($value): Hotel
    {
        $this->data['latitude'] = $value;
        return $this;
    }

    public function lng($value): Hotel
    {
        $this->data['longitude'] = $value;
        return $this;
    }

    public function hotelIds($value): Hotel
    {
        $this->data['hotelIds'] = $value;
        return $this;
    }

    public function roomQuantity($value): Hotel
    {
        $this->data['roomQuantity'] = $value;
        return $this;
    }

    public function radius($value): Hotel
    {
        $this->data['radius'] = $value;
        return $this;
    }
    public function radiusUnit($value): Hotel
    {
        $this->data['radiusUnit'] = $value;
        return $this;
    }
    public function paymentPolicy($value): Hotel
    {
        $this->data['paymentPolicy'] = $value;
        return $this;
    }
    public function includeClosed($value): Hotel
    {
        $this->data['includeClosed'] = $value;
        return $this;
    }
    public function bestRateOnly($value): Hotel
    {
        $this->data['bestRateOnly'] = $value;
        return $this;
    }
    public function view($value): Hotel
    {
        $this->data['view'] = $value;
        return $this;
    }
    public function sort($value): Hotel
    {
        $this->data['sort'] = $value;
        return $this;
    }
}

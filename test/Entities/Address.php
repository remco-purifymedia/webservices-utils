<?php

namespace WebservicesNl\Utils\Test\Entities;

/**
 * Class Address.
 */
class Address extends DummyClass
{
    /**
     * @var string
     */
    protected $city;

    /**
     * @var string
     */
    protected $country;

    /**
     * @var Geocode
     */
    protected $geoCode;

    /**
     * @var int
     */
    protected $houseNumber;

    /**
     * @var string
     */
    protected $postcode;

    /**
     * @var string
     */
    protected $streetName;

    /**
     * @var string
     */
    protected $streetSuffix;

    /**
     * @var bool
     */
    protected $valid = true;

    /**
     * @return mixed
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param mixed $city
     */
    public function setCity($city)
    {
        $this->city = $city;
    }

    /**
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @param string $country
     */
    public function setCountry($country)
    {
        $this->country = $country;
    }

    /**
     * @return Geocode
     */
    public function getGeoCode()
    {
        return $this->geoCode;
    }

    /**
     * @param Geocode $geoCode
     */
    public function setGeoCode($geoCode)
    {
        $this->geoCode = $geoCode;
    }

    /**
     * @return int
     */
    public function getHouseNumber()
    {
        return $this->houseNumber;
    }

    /**
     * @param int $houseNumber
     */
    public function setHouseNumber($houseNumber)
    {
        $this->houseNumber = $houseNumber;
    }

    /**
     * @return string
     */
    public function getPostcode()
    {
        return $this->postcode;
    }

    /**
     * @param string $postcode
     */
    public function setPostcode($postcode)
    {
        $this->postcode = $postcode;
    }

    /**
     * @return string
     */
    public function getStreetName()
    {
        return $this->streetName;
    }

    /**
     * @param string $streetName
     */
    public function setStreetName($streetName)
    {
        $this->streetName = $streetName;
    }

    /**
     * @return string
     */
    public function getStreetSuffix()
    {
        return $this->streetSuffix;
    }

    /**
     * @param string $streetSuffix
     */
    public function setStreetSuffix($streetSuffix)
    {
        $this->streetSuffix = $streetSuffix;
    }

    /**
     * @return bool
     */
    public function isValid()
    {
        return $this->valid;
    }

    /**
     * @param bool $valid
     */
    public function setValid($valid)
    {
        $this->valid = $valid;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            'city' => $this->city,
            'country' => $this->country,
            'geocode' => ($this->geoCode === null) ? null : $this->getGeoCode()->toArray(),
            'houseNumber' => $this->houseNumber,
            'postcode' => $this->postcode,
            'streetName' => $this->streetName,
            'streetSuffix' => $this->streetSuffix,
            'valid' => (bool) $this->isValid(),
        ];
    }
}

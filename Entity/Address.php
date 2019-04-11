<?php

namespace iiko\Entity;


class Address implements \JsonSerializable
{
    protected $city;
    protected $street;
    protected $home;
    protected $apartment;

    public function __construct($city, $street, $home)
    {
        if (mb_strlen($city) > 255) {
            throw new \InvalidArgumentException("Address city field is invalid. More then 259 is not allowed!. Input was {$city}");
        }

        if (mb_strlen($street) > 255) {
            throw new \InvalidArgumentException("Address street field is invalid. More then 259 is not allowed!. Input was {$street}");
        }

        if (mb_strlen($home) > 10) {
            throw new \InvalidArgumentException("Address home field is invalid. More then 10 is not allowed!. Input was {$home}");
        }

        $this->city = $city;
        $this->street = $street;
        $this->home = $home;
    }

    public function jsonSerialize()
    {
        $props =  [
            'city'    => $this->city,
            'street' => $this->street,
            'home' => $this->home,
            'apartment' => $this->apartment
        ];

        return array_filter($props, function($item) {
            return !is_null($item);
        });
    }

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
     * @return mixed
     */
    public function getStreet()
    {
        return $this->street;
    }

    /**
     * @param mixed $street
     */
    public function setStreet($street)
    {
        $this->street = $street;
    }

    /**
     * @return mixed
     */
    public function getHome()
    {
        return $this->home;
    }

    /**
     * @param mixed $home
     */
    public function setHome($home)
    {
        $this->home = $home;
    }

    /**
     * @return mixed
     */
    public function getApartment()
    {
        return $this->apartment;
    }

    /**
     * @param mixed $apartment
     */
    public function setApartment($apartment)
    {
        if (mb_strlen($apartment) > 10) {
            throw new \InvalidArgumentException("Address apartment field is invalid. More then 10 is not allowed!. Input was {$apartment}");
        }
        $this->apartment = $apartment;
    }

}
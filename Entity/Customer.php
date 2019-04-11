<?php

namespace iiko\Entity;

class Customer implements \JsonSerializable
{
    protected $id;
    protected $name;
    protected $phone;

    protected $phoneRegExp = '#^(8|\+?\d{1,3})?[ -]?\(?(\d{3})\)?[ -]?(\d{3})[ -]?(\d{2})[ -]?(\d{2})$#';

    public function __construct($name, $phone)
    {
        $this->name = trim($name);
        $this->phone = trim($phone);

        $this->validateName($name);
        $this->validatePhone($phone);
    }

    protected function validateName($name)
    {
        if (mb_strlen($name) > 60) {
            throw new \InvalidArgumentException("Customer name field is invalid. More then 60 is not allowed!. Input was {$name}");
        }
    }

    protected function validatePhone($phone)
    {
        if (!preg_match($this->phoneRegExp, $phone)) {
            throw new \InvalidArgumentException("Customer phone field is invalid!. Input was {$phone}");
        }
    }

    public function jsonSerialize()
    {
        $props =  [
            'id'   => $this->id,
            'name' => $this->name,
            'phone' => $this->phone
        ];

        return array_filter($props, function($item) {
           return !is_null($item);
        });
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param mixed $phone
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
    }


}
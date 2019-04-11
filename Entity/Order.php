<?php

namespace iiko\Entity;

class Order implements \JsonSerializable
{
    protected $id;
    protected $externalId;

    /**
     * @var DateTime
     */
    protected $date;

    /**
     * @var array OrderItem
     */
    protected $orderItems;
    protected $phone;
    protected $funSum;
    protected $address;
    protected $comment;
    protected $isSelfService;

    protected $phoneRegExp = '#^(8|\+?\d{1,3})?[ -]?\(?(\d{3})\)?[ -]?(\d{3})[ -]?(\d{2})[ -]?(\d{2})$#';

    public function __construct(\DateTime $date, $phone, $fullSum, $orderItems = [], $isSelfService = "true")
    {
        $this->date = $date;
        $this->phone = $phone;
        $this->funSum = $fullSum;
        $this->orderItems = $orderItems;
        $this->isSelfService = $isSelfService;

        $this->validatePhone($phone);
    }

    public function addOrderItems(array $orderItems)
    {
        foreach ($orderItems as $orderItem) {
            $this->addOrderItem($orderItem);
        }
    }

    public function addOrderItem(OrderItem $orderItem)
    {
        array_push($this->orderItems, $orderItem);
    }

    protected function validatePhone($phone)
    {
        if (!preg_match($this->phoneRegExp, $phone)) {
            throw new \InvalidArgumentException("Invalid phone number!. Input was {$phone})");
        }
    }

    public function setComment($comment)
    {
        $this->comment = $comment;
        $this->validateComment($comment);
    }

    public function getComment()
    {
        return $this->comment;
    }

    protected function validateComment($comment)
    {
        if(!$comment) {
            return true;
        }

        if(mb_strlen($comment) > 500) {
            throw new \InvalidArgumentException("Invalid Order comment field. More then 500 symbol is not allowed!. Input was {$comment}");
        }
    }


    public function jsonSerialize()
    {
        ////YYYY-MM-DD hh:mm:ss
        $props =  [
            'id'    => $this->id,
            'externalId' => $this->externalId,
            'date' => $this->date->format('Y-m-d H:i:s'),
            'items' => $this->orderItems,
            'phone' => $this->phone,
            'funSum' => $this->funSum,
            'comment' => $this->comment,
            'isSelfService' => $this->isSelfService,
            'address' => $this->address
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
    public function getExternalId()
    {
        return $this->externalId;
    }

    /**
     * @param mixed $externalId
     */
    public function setExternalId($externalId)
    {
        $this->externalId = $externalId;
    }

    /**
     * @return mixed
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param mixed $date
     */
    public function setDate($date)
    {
        $this->date = $date;
    }

    /**
     * @return mixed
     */
    public function getOrderItems()
    {
        return $this->orderItems;
    }

    /**
     * @param mixed $orderItems
     */
    public function setOrderItems($orderItems)
    {
        $this->orderItems = $orderItems;
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

    /**
     * @return mixed
     */
    public function getFunSum()
    {
        return $this->funSum;
    }

    /**
     * @param mixed $funSum
     */
    public function setFunSum($funSum)
    {
        $this->funSum = $funSum;
    }

    public function setAddress(Address $address) {
        $this->address = $address;
    }

}
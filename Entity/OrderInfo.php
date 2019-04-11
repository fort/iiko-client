<?php

namespace iiko\Entity;

class OrderInfo
{
    protected $orderId;
    protected $status;

    /**
     * OrderInfo constructor.
     * @param array <br>
     * $orderInfo['orderId']
     * $orderInfo['status']
     */
    public function __construct(array $orderInfo)
    {
        if (!empty($orderInfo['orderId'])) {
            $this->orderId = $orderInfo['orderId'];
        }

        if (!empty($orderInfo['status'])) {
            $this->status = $orderInfo['status'];
        }
    }

    public function setStatus($status)
    {
        $this->status = $status;
    }

    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @return mixed
     */
    public function getOrderId()
    {
        return $this->orderId;
    }

    /**
     * @param mixed $orderId
     */
    public function setOrderId($orderId)
    {
        $this->orderId = $orderId;
    }

}
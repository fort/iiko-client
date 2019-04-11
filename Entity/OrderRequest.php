<?php

namespace iiko\Entity;

class OrderRequest implements \JsonSerializable
{
    /**
     * @var string
     */
    protected $organizationId;
    /**
     * @var Customer
     */
    protected $customer;
    /**
     * @var Order
     */
    protected $order;

    /**
     * @var array
     */
    protected $customData;

    public function __construct($organizationId, Customer $customer, Order $order)
    {
        $this->organizationId = $organizationId;
        $this->customer = $customer;
        $this->order = $order;
    }

    public function jsonSerialize()
    {
        return [
            'organization' => $this->organizationId,
            'customer' => $this->customer,
            'order' => $this->order
        ];
    }

    /**
     * @return mixed
     */
    public function getOrganizationId()
    {
        return $this->organizationId;
    }

    /**
     * @param mixed $organization
     */
    public function setOrganizationId($organizationId)
    {
        $this->organizationId = $organizationId;
    }

    /**
     * @return Customer
     */
    public function getCustomer()
    {
        return $this->customer;
    }

    /**
     * @param Customer $customer
     */
    public function setCustomer($customer)
    {
        $this->customer = $customer;
    }

    /**
     * @return mixed
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * @param mixed $order
     */
    public function setOrder($order)
    {
        $this->order = $order;
    }

    /**
     * @return mixed
     */
    public function getCustomData()
    {
        return $this->customData;
    }

    /**
     * @param mixed $customData
     */
    public function setCustomData($customData)
    {
        $this->customData = $customData;
    }

}
<?php

namespace iiko\DAO;

use iiko\Entity;
use iiko\IikoClient;
use iiko\IikoRequest;

class Order
{
    /**
     * @var IikoClient
     */
    protected $iikoClient;
    /**
     * @var Entity\Order
     */
    protected $order;
    /**
     * @var Entity\Customer
     */
    protected $customer;
    protected $organizationId;
    protected $emailForFailedOrderInfo;

    protected $sandbox;

    public function __construct(
        IikoClient $httpClient,
        Entity\Order $order,
        Entity\Customer $customer,
        $organzationId)
    {
        $this->iikoClient = $httpClient;
        $this->order = $order;
        $this->customer = $customer;
        $this->organizationId = $organzationId;
    }

    public function setSendBox($sandBox)
    {
        $this->sandbox = $sandBox;
    }

    public function setEmailForFailedOrderInfo($email)
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new \InvalidArgumentException("Order setEmailForFailedOrderInfo field is invalid. Input was {$email}");
        }
        $this->emailForFailedOrderInfo = $email;
    }

    public function createOrder(Entity\Order $order = null, Entity\Customer $customer = null, $organzationId = null)
    {
        if ($order) {
            $this->order = $order;
        }

        if ($customer) {
            $this->customer = $order;
        }

        if ($organzationId) {
            $this->organizationId = $organzationId;
        }

        $params = [
            'organization' => $this->organizationId,
            'customer' => $this->customer,
            'order' => $this->order,
            'emailForFailedOrderInfo' => $this->emailForFailedOrderInfo
        ];

        $params = array_filter($params, function($item) {
            return !is_null($item);
        });

        $requestTimeout = '00:00:20';
        //TODO: move to config file
        $uri = 'orders/add?access_token=' . $this->iikoClient->getAccessToken()->getValue() . '&requestTimeout=' . $requestTimeout;

        $headers['Content-Type'] = 'application/json';
        $request = new IikoRequest('POST', $uri, $params, $headers);

        if ($this->sandbox) {
            return $this->fakeOrderResponse();
        } else {
            $response = $this->iikoClient->sendRequest($request);
            return new Entity\OrderInfo($response->getDecodedBody());
        }

    }

    public function fakeOrderResponse()
    {
        $rndOrderId = rand(1000, 100000);
        $orderInfoJson = "{
            \"orderId\": \"{$rndOrderId}\",
            \"status\": \"Awaiting delivery\"
        }";

        $json = json_decode($orderInfoJson, true);

        return new Entity\OrderInfo($json);
    }

}
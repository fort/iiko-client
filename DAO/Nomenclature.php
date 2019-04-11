<?php

namespace iiko\DAO;

use iiko\IikoRequest;

class Nomenclature
{
    protected $iikoClient;

    public function __construct($iikoClient)
    {
        $this->iikoClient = $iikoClient;
    }

    public function getList()
    {
        $params = [
            'access_token' => $this->iikoClient->getAccessToken()->getValue(),
        ];

        $request = new IikoRequest('GET', 'nomenclature/' . $this->iikoClient->getOrganizationId(), $params);
        $response = $this->iikoClient->sendRequest($request);

        $list = $response->getDecodedBody();

        return $list;

    }
}
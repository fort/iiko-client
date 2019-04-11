<?php
namespace iiko\DAO;

use iiko\Entity;
use iiko\IikoRequest;

class Organization
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
            'request_timeout' => '00:02:00'
        ];

        $request = new IikoRequest('GET', 'organization/list', $params);
        $response = $this->iikoClient->sendRequest($request);

        $list = $response->getDecodedBody();

        $entityList = [];
        if(!empty($list)) {
            foreach ($list as $item) {
                $org = new Entity\Organization();
                $org->address = $item['address'];
                $org->description = $item['description'];
                $entityList[] = $org;
            }
        }

        return $entityList;
    }
}
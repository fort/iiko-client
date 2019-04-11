<?php
namespace iiko\Authentication;

use iiko\IikoRequest;

class IikoAuthentication
{
    protected $iikoClient;

    public function __construct($iikoClient)
    {
        $this->iikoClient = $iikoClient;
    }

    /**
     * @return AccessToken
     * @throws \iiko\Exceptions\IIkoException
     */

    public function authenticate()
    {
        $params = [
            'user_id' => $this->iikoClient->getUserId(),
            'user_secret' => $this->iikoClient->getUserSecret(),
        ];

        $request = new IikoRequest('GET', 'auth/access_token', $params);
        $response = $this->iikoClient->sendRequest($request);

        return new AccessToken($response->getDecodedBody());
    }

}
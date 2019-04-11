<?php
namespace iiko;

use iiko\Exceptions\IIkoException;

class IikoClient
{
    const BASE_IIKO_API_URL = 'https://iiko.biz:9900/api';

    protected $httpClient;
    protected $userSecret;
    protected $userId;
    protected $apiVersion;
    protected $organizationId;
    /**
     * @var AccessToken
     */
    private $accessToken = null;

    public function __construct($userId, $userSecret, $organizationId, $apiVersion = 0)
    {
        $httpClientOptions = array(
            'base_uri' => self::BASE_IIKO_API_URL,
            'allow_redirects' => false,
            'timeout' => 20,
            'http_errors' => false,
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json'
            ]
        );
        $this->httpClient = new \GuzzleHttp\Client($httpClientOptions);

        if (!$userId) {
            throw new \InvalidArgumentException("Iiko user_id is required.");
        }

        if (!$userSecret) {
            throw new \InvalidArgumentException("Iiko user_secret is required.");
        }

        $this->userId = $userId;
        $this->userSecret = $userSecret;
        $this->apiVersion = $apiVersion;
        $this->organizationId = $organizationId;
    }

    /**
     * Obtain access token
     * @return AccessToken
     */
    public function getAccessToken()
    {
        if(is_null($this->accessToken)) {
            $iikoAuthenticate = new \iiko\Authentication\IikoAuthentication($this);
            $this->accessToken = $iikoAuthenticate->authenticate();
        }elseif ($this->accessToken instanceof \iiko\Authentication\AccessToken AND $this->accessToken->isExpired()) {
            $iikoAuthenticate = new \iiko\Authentication\IikoAuthentication($this);
            $this->accessToken = $iikoAuthenticate->authenticate();
        }else {
            $this->accessToken = new \iiko\Authentication\AccessToken('');
        }

        return $this->accessToken;
    }

    public function sendRequest(IikoRequest $request) {
        list($method, $url, $params, $headers) = $this->prepareRequestMessage($request);
        $request->setUrl($url);

        $requestOptions = [];
        //TODO: move getting header to external class
        if(isset($headers['Content-Type']) AND $headers['Content-Type'] == strtolower('application/json') ) {
            $requestOptions['headers'] = ['Content-Type' => 'application/json'];
        }
        $requestOptions['body'] = $params;

        try {
            $response =  $this->httpClient->request($method, $url, $requestOptions);
        }catch (\GuzzleHttp\Exception\RequestException $e) {
            throw new IIkoException($e->getMessage(), $e->getCode(), $e, $request);
        }

        $iikoResponse =  new IikoResponse(
            $request,
            (string) $response->getBody(),
            $response->getStatusCode(),
            $response->getHeaders()
        );

        if($iikoResponse->hasError()) {
            $iikoResponse->throwException();
        }

        return $iikoResponse;
    }

    public function getBaseUrl()
    {
        return self::BASE_IIKO_API_URL;
    }

    /**
     * @return mixed
     */
    public function getUserSecret()
    {
        return $this->userSecret;
    }

    /**
     * @param mixed $userSecret
     */
    public function setUserSecret($userSecret)
    {
        $this->userSecret = $userSecret;
    }

    /**
     * @return mixed
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * @param mixed $userId
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;
    }

    public function getOrganizationId()
    {
        return $this->organizationId;
    }

    /**
     * @return string
     */
    public function getGUID()
    {
        if (function_exists("com_create_guid"))
            return com_create_guid();

        mt_srand((double)microtime() * 10000);//optional for php 4.2.0 and up.
        $charId = md5(uniqid(rand(), true));
        $hyphen = chr(45);// "-"
        $uuid = substr($charId, 0, 8) . $hyphen
            . substr($charId, 8, 4) . $hyphen
            . substr($charId, 12, 4) . $hyphen
            . substr($charId, 16, 4) . $hyphen
            . substr($charId, 20, 12);

        return $uuid;
    }

    protected function prepareRequestMessage(IikoRequest $request)
    {
        $url = $this->getBaseUrl() . '/' . $this->apiVersion . '/' . $request->getUri();
        return [
            $request->getMethod(),
            $url,
            $request->getParams(),
            $request->getHeaders()
        ];
    }

}
<?php

namespace Bsadnu\GrandIDBundle\Services;

use Bsadnu\GrandIDBundle\DTO\FederatedLogin;
use GuzzleHttp\Client as HttpClient;

class GrandID
{
    const ERR_OBJ_CODES = [
            'APIKEYNOTVALID01',
            'FIELDSNOTVALID',
        ];

    /**
     * @var array
     */
    private $config;

    /** @var HttpClient */
    private $httpClient;

    /**
     * @var string
     */
    private $baseUrl;

    /**
     * @var string
     */
    private $apiKey;

    /**
     * @var string
     */
    private $authenticateServiceKey;

    /**
     * SlackBot constructor.
     *
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->setConfig($config);

        $this->httpClient = new HttpClient();

        $this->baseUrl = $this->config['base_url'];
        $this->apiKey = $this->config['api_key'];
        $this->authenticateServiceKey = $this->config['authenticate_service_key'];
    }

    /**
     * @return array
     */
    public function getConfig(): array
    {
        return $this->config;
    }

    /**
     * @param array $config
     */
    public function setConfig(array $config)
    {
        $this->config = $config;
    }

    public function federatedLogin($callbackUrl): ?FederatedLogin
    {
        $uri = $this->baseUrl . 'FederatedLogin?authenticateServiceKey=' . $this->authenticateServiceKey . '&apiKey=' . $this->apiKey . '&callbackUrl=' . $callbackUrl;

        $response = $this->httpClient->request('GET', $uri);

        $decodedResponseBody = json_decode($response->getBody());

        $output = null;
        if (property_exists($decodedResponseBody, 'sessionId')) {
            $output = new FederatedLogin($decodedResponseBody->sessionId, $decodedResponseBody->redirectUrl);
        }

        return $output;
    }

    public function federatedDirectLogin($username, $password)
    {
        $uri = $this->baseUrl . 'FederatedDirectLogin?authenticateServiceKey=' . $this->authenticateServiceKey . '&apiKey=' . $this->apiKey . '&username=' . $username . '&password=' . $password;

        $response = $this->httpClient->request('GET', $uri);

        return json_decode($response->getBody());
    }

    public function logout($sessionId)
    {
        $uri = $this->baseUrl . 'Logout?authenticateServiceKey=' . $this->authenticateServiceKey . '&apiKey=' . $this->apiKey . '&sessionid=' . $sessionId;

        $response = $this->httpClient->request('GET', $uri);

        return json_decode($response->getBody());
    }

    public function getSession($sessionId)
    {
        $uri = $this->baseUrl . 'GetSession?authenticateServiceKey=' . $this->authenticateServiceKey . '&apiKey=' . $this->apiKey . '&sessionid=' . $sessionId;

        $response = $this->httpClient->request('GET', $uri);

        return json_decode($response->getBody());
    }

    public function isLoginSuccessful($sessionId)
    {
        $sessionObject = $this->getSession($sessionId);

        return property_exists($sessionObject, 'username');
    }
}
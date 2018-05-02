<?php

namespace Bsadnu\GrandIDBundle\Services;

use Bsadnu\GrandIDBundle\DTO\FederatedLogin;
use Bsadnu\GrandIDBundle\DTO\SuccessfulSession;
use Bsadnu\GrandIDBundle\Entity\GrandIdSession;
use Doctrine\ORM\EntityManagerInterface;
use GuzzleHttp\Client as HttpClient;

class GrandID
{
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

    private $entityManager;

    /**
     * SlackBot constructor.
     *
     * @param array $config
     */
    public function __construct(array $config, EntityManagerInterface $entityManager)
    {
        $this->setConfig($config);

        $this->httpClient = new HttpClient();

        $this->baseUrl = $this->config['base_url'];
        $this->apiKey = $this->config['api_key'];
        $this->authenticateServiceKey = $this->config['authenticate_service_key'];

        $this->entityManager = $entityManager;
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
        $output = null;

        $uri = $this->baseUrl . 'FederatedLogin?authenticateServiceKey=' . $this->authenticateServiceKey . '&apiKey=' . $this->apiKey . '&callbackUrl=' . $callbackUrl;
        $response = $this->httpClient->request('GET', $uri);
        $decodedResponseBody = json_decode($response->getBody());

        if (property_exists($decodedResponseBody, 'sessionId')) {
            $sessionId = $decodedResponseBody->sessionId;
            $redirectUrl = $decodedResponseBody->redirectUrl;

            $this->addSessionToStorage($sessionId, false, $redirectUrl);

            $output = new FederatedLogin($sessionId, $redirectUrl);
        }

        return $output;
    }

    public function federatedLoginMock($callbackUrl, $host, $protocol): ?FederatedLogin
    {
        $output = null;

        $sessionId = $this->generateRandomSessionId();
        $redirectUrl = $this->getRedirectUrlBySessionId($sessionId, $callbackUrl, $host, $protocol);

        $this->addSessionToStorage($sessionId, true, $redirectUrl);

        $output = new FederatedLogin($this->generateRandomSessionId(), $redirectUrl);

        return $output;
    }

    public function federatedDirectLogin($username, $password): ?SuccessfulSession
    {
        $output = null;

        $uri = $this->baseUrl . 'FederatedDirectLogin?authenticateServiceKey=' . $this->authenticateServiceKey . '&apiKey=' . $this->apiKey . '&username=' . $username . '&password=' . $password;
        $response = $this->httpClient->request('GET', $uri);
        $decodedResponseBody = json_decode($response->getBody());

        if (property_exists($decodedResponseBody, 'username')) {
            $sessionId = $decodedResponseBody->sessionId;
            $responseUsername = $decodedResponseBody->username;

            $this->addSessionToStorage($sessionId, false, null, true, $responseUsername);

            $output = new SuccessfulSession($decodedResponseBody->sessionId, $decodedResponseBody->username);
        }

        return $output;
    }

    public function logout($sessionId): bool
    {
        $uri = $this->baseUrl . 'Logout?authenticateServiceKey=' . $this->authenticateServiceKey . '&apiKey=' . $this->apiKey . '&sessionid=' . $sessionId;
        $response = $this->httpClient->request('GET', $uri);
        $decodedResponseBody = json_decode($response->getBody());

        if (property_exists($decodedResponseBody, 'sessiondeleted') && ('1' == $decodedResponseBody->sessiondeleted)) {
            $this->logoutSessionInStorage($sessionId);

            return true;
        }

        return false;
    }

    public function logoutMock($sessionId): bool
    {
        return $this->logoutSessionInStorage($sessionId);
    }

    public function getSession($sessionId): ?SuccessfulSession
    {
        $output = null;

        $uri = $this->baseUrl . 'GetSession?authenticateServiceKey=' . $this->authenticateServiceKey . '&apiKey=' . $this->apiKey . '&sessionid=' . $sessionId;
        $response = $this->httpClient->request('GET', $uri);
        $decodedResponseBody = json_decode($response->getBody());

        if (property_exists($decodedResponseBody, 'username')) {
            $responseSessionId = $decodedResponseBody->sessionId;
            $username = $decodedResponseBody->username;

            $this->enableSession($responseSessionId, $username);

            $output = new SuccessfulSession($responseSessionId, $username);
        }

        return $output;
    }

    public function getSessionMock($sessionId): ?SuccessfulSession
    {
        $output = null;

        $session = $this->getMockSessionFromStorage($sessionId);

        if ($session && !is_null($session->getUsername())) {
            $output = new SuccessfulSession($session->getExternalId(), $session->getUsername());
        }

        return $output;
    }

    public function attachUsernameToMockSession($sessionId, $username): void
    {
        $session = $this->getMockSessionFromStorage($sessionId);
        $session->setUsername($username);
        $session->setUpdatedAt(new \DateTime);

        $this->entityManager->persist($session);
        $this->entityManager->flush();
    }

    public function logInMockSession($sessionId): bool
    {
        $session = $this->getMockSessionFromStorage($sessionId);
        if (!is_null($session->getUsername()) && $session->getIsMock()) {
            $session->setIsLoggedIn(true);
            $session->setUpdatedAt(new \DateTime);

            $this->entityManager->persist($session);
            $this->entityManager->flush();

            return true;
        } else {
            return false;
        }
    }

    private function enableSession($sessionId, $username): void
    {
        $session = $this->getSessionFromStorage($sessionId);
        $session->setUsername($username);
        $session->setIsLoggedIn(true);
        $session->setUpdatedAt(new \DateTime);

        $this->entityManager->persist($session);
        $this->entityManager->flush();
    }

    private function addSessionToStorage($sessionId, bool $isMock, $redirectUrl = null, $isLoggedIn = false, $username = null): void
    {
        $grandIdSession = new GrandIdSession();
        $grandIdSession->setExternalId($sessionId);
        $grandIdSession->setIsMock($isMock);
        $grandIdSession->setRedirectUrl($redirectUrl);
        $grandIdSession->setIsLoggedIn($isLoggedIn);
        $grandIdSession->setUsername($username);

        $this->entityManager->persist($grandIdSession);
        $this->entityManager->flush();
    }

    private function logoutSessionInStorage($sessionId): bool
    {
        $repository = $this->entityManager->getRepository(GrandIdSession::class);

        $session = $repository->findOneBy([
            'externalId' => $sessionId,
        ]);

        if (!$session) {
            return false;
        } else {
            $session->setIsLoggedIn(false);
            $session->setUpdatedAt(new \DateTime);

            $this->entityManager->persist($session);
            $this->entityManager->flush();

            return true;
        }
    }

    private function getSessionFromStorage($sessionId)
    {
        $repository = $this->entityManager->getRepository(GrandIdSession::class);

        return $repository->findOneBy([
            'externalId' => $sessionId,
            'isMock' => false,
        ]);

    }

    private function getMockSessionFromStorage($sessionId)
    {
        $repository = $this->entityManager->getRepository(GrandIdSession::class);

        return $repository->findOneBy([
            'externalId' => $sessionId,
            'isMock' => true,
        ]);

    }

    private function generateRandomSessionId(): string
    {
        return uniqid(mt_rand(), true);
    }

    private function getRedirectUrlBySessionId($sessionId, $callbackUrl, $host, $protocol = 'https'): string
    {
        return $protocol . '://' . $host . 'redirect=' . $callbackUrl . '?grandidsession=' . $sessionId;
    }
}
<?php

namespace Bsadnu\GrandIDBundle\DTO;

class FederatedLogin
{
    /** @var string */
    private $sessionId;

    /** @var string */
    private $redirectUrl;

    public function __construct(
        string  $sessionId = '',
        string  $redirectUrl = ''
    )
    {
        $this
            ->setSessionId($sessionId)
            ->setRedirectUrl($redirectUrl)
        ;
    }

    /**
     * @return string
     */
    public function getSessionId(): string
    {
        return $this->sessionId;
    }

    /**
     * @param string $sessionId
     *
     * @return FederatedLogin
     */
    public function setSessionId(string $sessionId): FederatedLogin
    {
        $this->sessionId = $sessionId;

        return $this;
    }

    /**
     * @return string
     */
    public function getRedirectUrl(): string
    {
        return $this->redirectUrl;
    }

    /**
     * @param string $redirectUrl
     *
     * @return FederatedLogin
     */
    public function setRedirectUrl(string $redirectUrl): FederatedLogin
    {
        $this->redirectUrl = $redirectUrl;

        return $this;
    }
}
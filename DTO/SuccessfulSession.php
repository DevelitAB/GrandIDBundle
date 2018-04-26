<?php

namespace Bsadnu\GrandIDBundle\DTO;

class SuccessfulSession
{
    /** @var string */
    private $sessionId;

    /** @var string */
    private $username;

    public function __construct(
        string  $sessionId = '',
        string  $username = ''
    )
    {
        $this
            ->setSessionId($sessionId)
            ->setUsername($username)
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
     * @return SuccessfulSession
     */
    public function setSessionId(string $sessionId): SuccessfulSession
    {
        $this->sessionId = $sessionId;

        return $this;
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @param string $username
     *
     * @return SuccessfulSession
     */
    public function setUsername(string $username): SuccessfulSession
    {
        $this->username = $username;

        return $this;
    }
}
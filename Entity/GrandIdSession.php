<?php

namespace Bsadnu\GrandIDBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Bsadnu\GrandIDBundle\Repository\GrandIdSessionRepository")
 */
class GrandIdSession
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer", options={
     *     "unsigned": true
     * })
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=1024)
     */
    private $externalId;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true, length=1024)
     */
    private $redirectUrl;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true, length=256)
     */
    private $username;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isLoggedIn = false;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isMock = false;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
    }

    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getExternalId(): string
    {
        return $this->externalId;
    }

    /**
     * @param string $externalId
     */
    public function setExternalId(string $externalId): void
    {
        $this->externalId = $externalId;
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
     */
    public function setRedirectUrl(string $redirectUrl): void
    {
        $this->redirectUrl = $redirectUrl;
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
     */
    public function setUsername(string $username): void
    {
        $this->username = $username;
    }

    /**
     * @return bool
     */
    public function getIsLoggedIn(): bool
    {
        return $this->isLoggedIn;
    }

    /**
     * @param bool $isLoggedIn
     */
    public function setIsLoggedIn(bool $isLoggedIn): void
    {
        $this->isLoggedIn = $isLoggedIn;
    }

    /**
     * @return bool
     */
    public function getIsMock(): bool
    {
        return $this->isMock;
    }

    /**
     * @param bool $isMock
     */
    public function setIsMock(bool $isMock): void
    {
        $this->isMock = $isMock;
    }

    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function getUpdatedAt(): \DateTime
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTime $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }
}

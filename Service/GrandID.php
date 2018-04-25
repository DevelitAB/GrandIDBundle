<?php

namespace Bsadnu\GrandIDBundle\Service;

class GrandID
{
    /**
     * @var array
     */
    private $config;

    /**
     * SlackBot constructor.
     *
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->setConfig($config);
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

    public function someFunc()
    {
        return $this->config['base_url'];
    }
}
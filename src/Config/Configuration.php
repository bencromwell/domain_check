<?php

namespace DomainCheck\Config;

class Configuration
{
    /** @var string */
    protected $notify;

    /** @var string */
    protected $from;

    /** @var string[] */
    protected $domains;

    public function getNotify(): string
    {
        return $this->notify;
    }

    public function getFrom(): string
    {
        return $this->from;
    }

    public function getDomains(): array
    {
        return $this->domains;
    }

    public function __construct($parsedYaml)
    {
        $this->notify = $parsedYaml['notify'];
        $this->from = $parsedYaml['from'];
        $this->domains = $parsedYaml['domains'];
    }
}

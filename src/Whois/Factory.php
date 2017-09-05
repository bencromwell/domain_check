<?php

namespace DomainCheck\Whois;

class Factory
{
    public function whoisService(): WhoisService
    {
        return new Whois();
    }
}

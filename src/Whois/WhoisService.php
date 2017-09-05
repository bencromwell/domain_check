<?php

namespace DomainCheck\Whois;

interface WhoisService
{
    public function forDomain(string $domain): WhoisResult;
}

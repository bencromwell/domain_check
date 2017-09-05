<?php

namespace DomainCheck\Notify;

use DomainCheck\Whois\WhoisResult;

interface Notifier
{
    public function sendNotification(string $to, string $from, string $domain, WhoisResult $whoisResult): bool;
}

<?php

namespace DomainCheck\Notify;

use DomainCheck\Whois\WhoisResult;

class Email implements Notifier
{
    public function sendNotification(string $to, string $from, WhoisResult $whoisResult): bool
    {
        echo 'dummy send! ' . $to . PHP_EOL . print_r($whoisResult, true) . PHP_EOL;

        return true;
    }
}

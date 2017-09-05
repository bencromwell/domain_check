<?php

namespace DomainCheck\Notify;

interface Notifier
{
    public function sendNotification(string $to, string $from, string $contents): bool;
}

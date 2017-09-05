<?php

namespace DomainCheck\Notify;

class Factory
{
    public function notifier(): Notifier
    {
        return new Email();
    }
}

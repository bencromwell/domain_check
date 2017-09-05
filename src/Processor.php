<?php

namespace DomainCheck;

use DomainCheck\Config\Configuration;
use DomainCheck\Notify\Notifier;
use DomainCheck\Storage\Storage;
use DomainCheck\Whois\WhoisResult;
use DomainCheck\Whois\WhoisService;

class Processor
{
    /** @var Configuration */
    protected $configuration;

    /** @var WhoisService */
    protected $whois;

    /** @var Notifier */
    protected $notifier;

    /** @var Storage */
    protected $storage;

    public function __construct(Configuration $configuration, WhoisService $whois, Notifier $notifier, Storage $storage)
    {
        $this->configuration = $configuration;
        $this->whois = $whois;
        $this->notifier = $notifier;
        $this->storage = $storage;
    }

    public function processDomains()
    {
        foreach ($this->configuration->getDomains() as $domain) {
            $this->processDomain($domain);
        }
    }

    protected function processDomain(string $domain)
    {
        $domainResult = $this->whois->forDomain($domain);

        $lastRun = $this->storage->read($domain);

        if ($this->shouldNotify($domainResult, $lastRun)) {
            $this->notifier->sendNotification(
                $this->configuration->getNotify(),
                $this->configuration->getFrom(),
                $domainResult->getNotificationContents()
            );
        }

        $this->storage->save($domainResult, $domain);
    }

    protected function shouldNotify(WhoisResult $domainResult, ?WhoisResult $lastRun): bool
    {
        return is_null($lastRun) || $domainResult->isDifferentTo($lastRun);
    }
}

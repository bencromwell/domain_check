<?php

namespace DomainCheck;

use DomainCheck\Config\Configuration;
use DomainCheck\Notify\Notifier;
use DomainCheck\Storage\Storage;
use DomainCheck\Whois\WhoisResult;
use DomainCheck\Whois\WhoisService;
use Psr\Log\LoggerInterface;

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

    /** @var LoggerInterface */
    protected $logger;

    public function __construct(
        Configuration $configuration,
        WhoisService $whois,
        Notifier $notifier,
        Storage $storage,
        LoggerInterface $logger
    )
    {
        $this->configuration = $configuration;
        $this->whois = $whois;
        $this->notifier = $notifier;
        $this->storage = $storage;
        $this->logger = $logger;
    }

    public function processDomains()
    {
        foreach ($this->configuration->getDomains() as $domain) {
            $this->processDomain($domain);
        }
    }

    protected function processDomain(string $domain)
    {
        $logContext = ['domain' => $domain];

        $this->logger->notice('Processing domain', $logContext);

        $domainResult = $this->whois->forDomain($domain);

        $lastRun = $this->storage->read($domain);

        if (is_null($lastRun)) {
            $this->logger->notice('This is the first run', $logContext);
        }

        if ($this->shouldNotify($domainResult, $lastRun)) {
            $this->logger->notice('Sending a notification', $logContext);

            $notified = $this->notifier->sendNotification(
                $this->configuration->getNotify(),
                $this->configuration->getFrom(),
                $domain,
                $domainResult
            );

            if ($notified) {
                $this->logger->notice('Notification successful', $logContext);
            } else {
                $this->logger->error('Notification failed', $logContext);
            }

        } else {
            $this->logger->notice('Not sending a notification', $logContext);
        }

        $this->storage->save($domainResult, $domain);
    }

    protected function shouldNotify(WhoisResult $domainResult, ?WhoisResult $lastRun): bool
    {
        return is_null($lastRun) || $domainResult->isDifferentTo($lastRun);
    }
}

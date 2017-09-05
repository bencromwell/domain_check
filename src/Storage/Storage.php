<?php

namespace DomainCheck\Storage;

use DomainCheck\Whois\WhoisResult;
use League\Flysystem\FilesystemInterface;

class Storage
{
    const ROOT = 'domain_check';

    /** @var FilesystemInterface */
    protected $flysystem;

    public function __construct(FilesystemInterface $flysystem)
    {
        $this->flysystem = $flysystem;
    }

    public function save(WhoisResult $whoisResult, string $reference)
    {
        $this->flysystem->write($reference, json_encode($whoisResult));
    }

    public function read(string $reference): ?WhoisResult
    {
        $json = $this->flysystem->read($reference);

        if (!$json) {
            return null;
        }

        return new WhoisResult(json_decode($json, true));
    }
}

<?php

namespace DomainCheck\Storage;

use DomainCheck\Whois\WhoisResult;
use League\Flysystem\FileNotFoundException;
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
        $body = json_encode($whoisResult);

        if ($this->flysystem->has($reference)) {
            $this->flysystem->update($reference, $body);
        } else {
            $this->flysystem->write($reference, $body);
        }
    }

    public function read(string $reference): ?WhoisResult
    {
        try {
            $json = $this->flysystem->read($reference);

            if (!$json) {
                return null;
            }

            return new WhoisResult(json_decode($json, true));
        } catch (FileNotFoundException $e) {
            return null;
        }
    }
}

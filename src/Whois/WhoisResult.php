<?php

namespace DomainCheck\Whois;

use JsonSerializable;

/**
 * Class WhoisResult
 * @package DomainCheck\Whois
 *
 * @property string DomainName
 * @property string RegistryDomainID
 * @property string RegistrarWHOISServer
 * @property string RegistrarURL
 * @property string UpdatedDate
 * @property string CreationDate
 * @property string RegistryExpiryDate
 * @property string Registrar
 * @property string RegistrarIANAID
 * @property string RegistrarAbuseContactEmail
 * @property string RegistrarAbuseContactPhone
 * @property string DomainStatus
 * @property string DNSSEC
 */
class WhoisResult implements JsonSerializable
{
    protected $data = [];

    public function __construct(array $whoisData)
    {
        $this->data = $whoisData;
    }

    public function __get($name)
    {
        return $this->data[$name] ?? null;
    }

    public function jsonSerialize()
    {
        return $this->data;
    }

    public function isDifferentTo(self $whoisResult): bool
    {
        return $this->jsonSerialize() !== $whoisResult->jsonSerialize();
    }

    public function getNotificationContents(): string
    {
        return print_r($this->jsonSerialize(), true);
    }
}

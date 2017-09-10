<?php

namespace DomainCheckTest\Whois;

use DomainCheck\Whois\WhoisResult;
use PHPUnit\Framework\TestCase;

class WhoisResultTest extends TestCase
{
    public function testJson()
    {
        $whoisResult = $this->newInstance();

        $json = json_encode($whoisResult);

        $expected = <<<JSON
{"DomainName":"DomainName","RegistryDomainID":"RegistryDomainID","RegistrarWHOISServer":"RegistrarWHOISServer","RegistrarURL":"RegistrarURL","UpdatedDate":"UpdatedDate","CreationDate":"CreationDate","RegistryExpiryDate":"RegistryExpiryDate","Registrar":"Registrar","RegistrarIANAID":"RegistrarIANAID","RegistrarAbuseContactEmail":"RegistrarAbuseContactEmail","RegistrarAbuseContactPhone":"RegistrarAbuseContactPhone","DomainStatus":"DomainStatus","DNSSEC":"DNSSEC"}
JSON;

        $this->assertEquals($expected, $json);
    }

    public function testIsDifferentTo()
    {
        $whoisResult = $this->newInstance();
        $another = clone $whoisResult;

        $this->assertFalse($whoisResult->isDifferentTo($another));
        $this->assertFalse($another->isDifferentTo($whoisResult));

        $another->DNSSEC = 42;

        $this->assertNotEquals($another->DNSSEC, $whoisResult->DNSSEC);

        $this->assertTrue($whoisResult->isDifferentTo($another));
        $this->assertTrue($another->isDifferentTo($whoisResult));
    }

    protected function newInstance(): WhoisResult
    {
        return new WhoisResult([
            'DomainName' => 'DomainName',
            'RegistryDomainID' => 'RegistryDomainID',
            'RegistrarWHOISServer' => 'RegistrarWHOISServer',
            'RegistrarURL' => 'RegistrarURL',
            'UpdatedDate' => 'UpdatedDate',
            'CreationDate' => 'CreationDate',
            'RegistryExpiryDate' => 'RegistryExpiryDate',
            'Registrar' => 'Registrar',
            'RegistrarIANAID' => 'RegistrarIANAID',
            'RegistrarAbuseContactEmail' => 'RegistrarAbuseContactEmail',
            'RegistrarAbuseContactPhone' => 'RegistrarAbuseContactPhone',
            'DomainStatus' => 'DomainStatus',
            'DNSSEC' => 'DNSSEC',
        ]);
    }
}

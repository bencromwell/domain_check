<?php

namespace DomainCheckTest\Whois;

use DomainCheck\Whois\Whois;
use DomainCheck\Whois\WhoisResult;
use Mockery\Mock;
use PHPUnit\Framework\TestCase;

class WhoisTest extends TestCase
{
    public function testParsesEmptyResultWithoutFailure()
    {
        $whois = $this->getWhois('');

        $whoisResult = $whois->forDomain('testdomainone.co.uk');

        $this->assertInstanceOf(WhoisResult::class, $whoisResult);
    }

    public function testParsesIcann()
    {
        $icann = <<<TEXT
   Domain Name: BENCROMWELL.COM
   Registry Domain ID: 1591358656_DOMAIN_COM-VRSN
   Registrar WHOIS Server: whois.enom.com
   Registrar URL: http://www.enom.com
   Updated Date: 2017-03-11T13:25:19Z
   Creation Date: 2010-04-03T14:42:58Z
   Registry Expiry Date: 2018-04-03T14:42:58Z
   Registrar: eNom, Inc.
   Registrar IANA ID: 48
   Registrar Abuse Contact Email:
   Registrar Abuse Contact Phone:
   Domain Status: clientTransferProhibited https://icann.org/epp#clientTransferProhibited
   Name Server: NS1.DIGITALOCEAN.COM
   Name Server: NS2.DIGITALOCEAN.COM
   Name Server: NS3.DIGITALOCEAN.COM
   DNSSEC: unsigned
   URL of the ICANN Whois Inaccuracy Complaint Form: https://www.icann.org/wicf/
>>> Last update of whois database: 2017-09-07T11:08:19Z <<<

For more information on Whois status codes, please visit https://icann.org/epp

NOTICE: The expiration date displayed in this record is the date the
registrar's sponsorship of the domain name registration in the registry is
currently set to expire. This date does not necessarily reflect the expiration
date of the domain name registrant's agreement with the sponsoring
registrar.  Users may consult the sponsoring registrar's Whois database to
view the registrar's reported date of expiration for this registration.

TERMS OF USE: You are not authorized to access or query our Whois
database through the use of electronic processes that are high-volume and
automated except as reasonably necessary to register domain names or
modify existing registrations; the Data in VeriSign Global Registry
Services' ("VeriSign") Whois database is provided by VeriSign for
information purposes only, and to assist persons in obtaining information
about or related to a domain name registration record. VeriSign does not
guarantee its accuracy. By submitting a Whois query, you agree to abide
by the following terms of use: You agree that you may use this Data only
for lawful purposes and that under no circumstances will you use this Data
to: (1) allow, enable, or otherwise support the transmission of mass
unsolicited, commercial advertising or solicitations via e-mail, telephone,
or facsimile; or (2) enable high volume, automated, electronic processes
that apply to VeriSign (or its computer systems). The compilation,
repackaging, dissemination or other use of this Data is expressly
prohibited without the prior written consent of VeriSign. You agree not to
use electronic processes that are automated and high-volume to access or
query the Whois database except as reasonably necessary to register
domain names or modify existing registrations. VeriSign reserves the right
to restrict your access to the Whois database in its sole discretion to ensure
operational stability.  VeriSign may restrict or terminate your access to the
Whois database for failure to abide by these terms of use. VeriSign
reserves the right to modify these terms at any time.

The Registry database contains ONLY .COM, .NET, .EDU domains and
Registrars.

TEXT;

        $whois = $this->getWhois($icann);

        $whoisResult = $whois->forDomain('testdomainone.co.uk');

        $this->assertInstanceOf(WhoisResult::class, $whoisResult);

        $this->assertEquals('2018-04-03T14:42:58Z', $whoisResult->RegistryExpiryDate);
    }

    public function testParsesNominet()
    {
        $nominet = <<<TEXT
    Domain name:
        bencromwell.co.uk

    Registrant:
        Ben Cromwell

    Registrant type:
        UK Individual

    Registrant's address:
        The registrant is a non-trading individual who has opted to have their
        address omitted from the WHOIS service.

    Data validation:
        Nominet was able to match the registrant's name and address against a 3rd party data source on 10-Dec-2012

    Registrar:
        LCN.com Ltd [Tag = LCN]
        URL: http://www.lcn.com

    Relevant dates:
        Registered on: 29-Dec-2009
        Expiry date:  29-Dec-2018
        Last updated:  17-Mar-2017

    Registration status:
        Registered until expiry date.

    Name servers:
        chris.ns.cloudflare.com
        cortney.ns.cloudflare.com

    WHOIS lookup made at 12:08:03 07-Sep-2017

-- 
This WHOIS information is provided for free by Nominet UK the central registry
for .uk domain names. This information and the .uk WHOIS are:

    Copyright Nominet UK 1996 - 2017.

You may not access the .uk WHOIS or use any data from it except as permitted
by the terms of use available in full at http://www.nominet.uk/whoisterms,
which includes restrictions on: (A) use of the data for advertising, or its
repackaging, recompilation, redistribution or reuse (B) obscuring, removing
or hiding any or all of this notice and (C) exceeding query rate or volume
limits. The data is provided on an 'as-is' basis and may lag behind the
register. Access may be withdrawn or restricted at any time. 

TEXT;

        $whois = $this->getWhois($nominet);

        $whoisResult = $whois->forDomain('testdomainone.co.uk');

        $this->assertInstanceOf(WhoisResult::class, $whoisResult);

        $this->assertEquals('29-Dec-2018', $whoisResult->RegistryExpiryDate);
    }

    protected function getWhois(string $rawResult): Whois
    {
        /** @var Whois $mock */
        $mock = \Mockery::mock(Whois::class, function ($mock) use ($rawResult) {
            /** @var Mock $mock */
            $mock->shouldDeferMissing()->shouldAllowMockingProtectedMethods();
            $mock->shouldReceive('rawWhois')->andReturn($rawResult);

            return $mock;
        });

        return $mock;
    }
}

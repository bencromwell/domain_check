<?php

use DomainCheck\{
    Config,
    Notify,
    Whois,
    Storage,
    Processor
};

require_once __DIR__ . '/vendor/autoload.php';

$config = (new Config\Factory())->fromYamlFile(__DIR__ . '/config.yml');
$notifier = (new Notify\Factory())->notifier();
$whois = (new Whois\Factory())->whoisService();
$storage = (new Storage\Factory())->storage();

$processor = new Processor($config, $whois, $notifier, $storage);
$processor->processDomains();

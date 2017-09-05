<?php

namespace DomainCheck\Config;

use Symfony\Component\Yaml\Yaml;

class Factory
{
    public function fromYamlFile(string $fileName): Configuration
    {
        return $this->fromYaml(file_get_contents($fileName));
    }

    public function fromYaml(string $yaml): Configuration
    {
        return new Configuration(Yaml::parse($yaml));
    }
}

<?php

namespace DomainCheck\Whois;

class Whois implements WhoisService
{
    public function forDomain(string $domain): WhoisResult
    {
        $rawResult = shell_exec('whois ' . $domain);

        $parsedResult = $this->parseResult($rawResult);

        return new WhoisResult($parsedResult);
    }

    protected function parseResult(string $whoisResult): array
    {
        $lines = explode("\n", $whoisResult);

        $data = [];

        foreach ($lines as $line) {
            if ($this->useThisLine($line)) {
                list($key, $value) = explode(': ', trim($line));
                $key = str_replace(' ', '', $key);
                $data[$key] = $value;
            }
        }

        return $data;
    }

    /**
     * If a line starts with three spaces we want it, otherwise it's just the fluff from the whois result
     *
     * @param string $line
     * @return bool
     */
    protected function useThisLine(string $line): bool
    {
        return substr($line, 0, 3) === '   ';
    }
}

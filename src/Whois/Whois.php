<?php

namespace DomainCheck\Whois;

class Whois implements WhoisService
{
    public function forDomain(string $domain): WhoisResult
    {
        $rawResult = $this->rawWhois($domain);

        $parsedResult = $this->parseResult($rawResult);

        return new WhoisResult($parsedResult);
    }

    protected function rawWhois(string $domain): string
    {
        return shell_exec('whois ' . $domain);
    }

    protected function parseResult(string $whoisResult): array
    {
        $lines = explode("\n", $whoisResult);

        $data = [];

        foreach ($lines as $line) {
            if ($this->useThisLine($line)) {
                $line = trim($line);

                if (strlen($line) > 0 && strpos($line, ': ') !== false) {
                    list($key, $value) = explode(': ', $line);
                    $key = str_replace(' ', '', $key);
                    $data[$key] = $value;
                }
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

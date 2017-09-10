# domain_check
Checks WHOIS results for differences, notifies if found

## Config

```yml
---
notify: me@example.com # can be a Slack channel, email address, etc., depending on the Notifier instance
from: myserver@example.com
domains:
  - example.com
  - example.org

```

## Caveats

Only works with .com TLD at present

## Architecture

Designed for exstensibility. Trivial to change the WHOIS service to an API - just follow the contract and map the results to the WhoisResult object.

Likewise for the Notifier contract - it expects a string from the config file which could be a comma separated list of emails or it could be a Slack channel reference or whatever. 

The Storage uses Flysystem but again it would be trivial to extract an interface and back it with Sqlite or something.

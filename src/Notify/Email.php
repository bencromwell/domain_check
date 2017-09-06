<?php

namespace DomainCheck\Notify;

use DomainCheck\Whois\WhoisResult;

class Email implements Notifier
{
    public function sendNotification(string $to, string $from, string $domain, WhoisResult $whoisResult): bool
    {
        $message = new \Swift_Message('Domain check results: ' . $domain);

        $message->setFrom($from);
        $message->setTo(explode(',', $to));

        $message->setBody($this->getHtml($whoisResult), 'text/html');

        return $this->send($message);
    }

    protected function getHtml(WhoisResult $whoisResult)
    {
        $rows = '';

        foreach ($whoisResult->jsonSerialize() as $key => $value) {
            $rows .= '<tr><th>' . $key . '</th><td>' . $value . '</td></tr>';
        }

        return '<table>' . $rows . ' </table>';
    }

    protected function send(\Swift_Message $message): bool
    {
        $transport = new \Swift_SendmailTransport();
        $mailer = new \Swift_Mailer($transport);

        return $mailer->send($message);
    }
}

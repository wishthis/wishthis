<?php

/**
 * Send an email
 *
 * Requires MJML input.
 *
 * @see https://mjml.io
 *
 * @author Jay Trees <github.jay@grandel.anonaddy.me>
 */

namespace wishthis;

class Email
{
    public function __construct(
        private string $to,
        private string $subject,
        private string $mjml
    ) {
    }

    public function send()
    {
        $to      = $this->to;
        $subject = $this->subject;
        $message = $this->mjml;
        $headers = array(
            'From'         => 'no-reply@' . $_SERVER['HTTP_HOST'],
            'Content-type' => 'text/html; charset=utf-8',
        );

        $success = mail($to, $subject, $message, $headers);
    }
}

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

use Qferrer\Mjml\Renderer\ApiRenderer;

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
        global $options;

        $renderer = new ApiRenderer(
            $options->getOption('mjml_api_key'),
            $options->getOption('mjml_api_secret')
        );

        $html = $this->mjml;

        if (ENV_IS_DEV) {
            /**
             * Ignore SSL certificate errors
             */
            try {
                $html = $renderer->render($this->mjml);
            } catch (\GuzzleHttp\Exception\RequestException $th) {
                error_log($th->getMessage());
            }
        } else {
            $html = $renderer->render($this->mjml);
        }


        $to      = $this->to;
        $subject = $this->subject;
        $message = $html;
        $headers = array(
            'From'         => 'no-reply@' . $_SERVER['HTTP_HOST'],
            'Content-type' => 'text/html; charset=utf-8',
        );

        $success = mail($to, $subject, $message, $headers);
    }
}

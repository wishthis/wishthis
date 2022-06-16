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
    public string $mjml;

    public function __construct(
        private string $to,
        private string $subject,
        private string $template,
        private string $part
    ) {
        $this->mjml = file_get_contents(ROOT . '/src/mjml/' . $this->template . '.mjml');
        $this->mjml = str_replace('MJML_PART', 'parts/' . $this->part . '.mjml', $this->mjml);
    }

    public function send(): bool
    {
        global $options;

        $renderer = new ApiRenderer(
            $options->getOption('mjml_api_key'),
            $options->getOption('mjml_api_secret')
        );

        $html = $this->mjml;

        if (defined('ENV_IS_DEV') && ENV_IS_DEV) {
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

        return $success;
    }
}

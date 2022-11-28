<?php

namespace Qferrer\Mjml;

use Qferrer\Mjml\Exception\ApiException;

interface ApiInterface
{
    /**
     * Renders a MJML to HTML content.
     *
     * @param string $mjml The MJML content
     *
     * @return string The generated HTML
     *
     * @throws ApiException
     */
    public function getHtml(string $mjml): string;

    /**
     * Returns the version of MJML used by the API to transpile.
     *
     * @return string
     */
    public function getMjmlVersion(): string;
}

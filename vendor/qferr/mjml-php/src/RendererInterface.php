<?php

namespace Qferrer\Mjml;

/**
 * Interface RendererInterface
 */
interface RendererInterface
{
    /**
     * Renders MJML to HTML content.
     *
     * @param string $content The MJML content
     *
     * @return string The generated HTML
     */
    public function render(string $content): string;
}

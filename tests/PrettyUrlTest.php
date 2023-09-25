<?php

declare(strict_types=1);

namespace wishthis\Tests\Api;

use PHPUnit\Framework\TestCase;
use wishthis\URL;

final class PrettyUrlTest extends TestCase
{
    public function testPrettyUrl(): void
    {
        \define('ROOT', \dirname(__DIR__));

        $requestUris = array(
            '//api/database-test',
            '/api/database-test',
            'api/database-test',
            'http://wishthis.online.localhost/index.php/api/database-test',
            'http://wishthis.online.localhost/index.php//api/database-test',
        );

        require __DIR__ . '/../src/classes/wishthis/URL.php';

        $expected_GET = array(
            'page'   => 'api',
            'module' => 'database-test',
        );

        foreach ($requestUris as $requestUri) {
            $url = new URL($requestUri);

            $this->assertSame($expected_GET, $_GET, $requestUri);
        }
    }
}

<?php

declare(strict_types=1);

namespace wishthis\Tests\Api;

use PHPUnit\Framework\TestCase;

final class CreateWishTest extends TestCase
{
    private int $testWishlistId = 5;

    private function apiRequest(string $endpoint, string $method, array $data = array()): string|false
    {
        $queryString = http_build_query($data);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_URL, $endpoint);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $queryString);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        switch ($method) {
            case 'POST':
                curl_setopt($ch, CURLOPT_POST, true);
                break;
        }

        $response = curl_exec($ch);

        return $response;
    }

    public function testTitleOnly(): void
    {
        $apiResponse = $this->apiRequest(
            'http://wishthis.online.localhost/api/wishes',
            'POST',
            array(
                'wish_title'  => 'WD Red SA500 NAS SATA SSD 2TB 2.5": Amazon.de: Computer & Accessories',
                'wishlist_id' => $this->testWishlistId,
            )
        );

        $this->assertNotFalse($apiResponse);

        $json = \json_decode($apiResponse, true);
        $this->assertNotNull($json);
        $this->assertTrue($json['success']);
        $this->assertEmpty($json['warning'], 'There has been unexpected output.');
    }
}

<?php

declare(strict_types=1);

namespace wishthis\Tests\Api;

use PHPUnit\Framework\TestCase;

final class CreateWishTest extends TestCase
{
    private int $testWishlistId = 5;

    private function apiRequest(string $endpoint, int $method, array $data = array()): string|false
    {
        $queryString = http_build_query($data);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_URL, $endpoint);
        curl_setopt($ch, $method, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $queryString);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);

        return $response;
    }

    public function testTitleOnly(): void
    {
        $apiResponse = $this->apiRequest(
            'http://wishthis.online.localhost/api/wishes',
            \CURLOPT_POST,
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

    public function testTitleOver128Chars(): void
    {
        $apiResponse = $this->apiRequest(
            'http://wishthis.online.localhost/api/wishes',
            \CURLOPT_POST,
            array(
                'wish_title'  => '0123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789',
                'wishlist_id' => $this->testWishlistId,
            )
        );
        $this->assertNotFalse($apiResponse);

        $json = \json_decode($apiResponse, true);
        $this->assertNotNull($json);
        $this->assertTrue($json['success']);
        $this->assertEmpty($json['warning'], 'There has been unexpected output.');
    }

    /**
     * Move this into a different testing class
     *
     * @return void
     */
//    public function testTitleWithSpecialCharacters(): void
//    {
//        $expectedTitle = '!"§$%&/()=?´öäü+#,.-*\'_:;~´`^âàûúôò';
//
//        $apiResponse = $this->apiRequest(
//            'http://wishthis.online.localhost/api/wishes',
//            \CURLOPT_POST,
//            array(
//                'wish_title'  => $expectedTitle,
//                'wishlist_id' => $this->testWishlistId,
//            )
//        );
//        $this->assertNotFalse($apiResponse);
//
//        $json = \json_decode($apiResponse, true);
//        $this->assertNotNull($json);
//        $this->assertTrue($json['success']);
//        $this->assertEmpty($json['warning'], 'There has been unexpected output.');
//    }

    public function testDescriptionOnly(): void
    {
        $apiResponse = $this->apiRequest(
            'http://wishthis.online.localhost/api/wishes',
            \CURLOPT_POST,
            array(
                'wish_description' => 'WD Red SA500 NAS SATA SSD 2TB 2.5": Amazon.de: Computer & Accessories',
                'wishlist_id'      => $this->testWishlistId,
            )
        );
        $this->assertNotFalse($apiResponse);

        $json = \json_decode($apiResponse, true);
        $this->assertNotNull($json);
        $this->assertTrue($json['success']);
        $this->assertEmpty($json['warning'], 'There has been unexpected output.');
    }

    public function testUrlOnly(): void
    {
        $apiResponse = $this->apiRequest(
            'http://wishthis.online.localhost/api/wishes',
            \CURLOPT_POST,
            array(
                'wish_url'    => 'https://www.amazon.com/Red-SA500-NAS-NAND-Internal/dp/B07YFGG261',
                'wishlist_id' => $this->testWishlistId,
            )
        );
        $this->assertNotFalse($apiResponse);

        $json = \json_decode($apiResponse, true);
        $this->assertNotNull($json);
        $this->assertTrue($json['success']);
        $this->assertEmpty($json['warning'], 'There has been unexpected output.');
    }

    public function testUrlOver255Chars(): void
    {
        $apiResponse = $this->apiRequest(
            'http://wishthis.online.localhost/api/wishes',
            \CURLOPT_POST,
            array(
                'wish_url'    => 'https://www.amazon.com/Red-SA500-NAS-NAND-Internal/dp/B07YFGG261?012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789',
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

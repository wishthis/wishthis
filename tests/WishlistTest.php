<?php

declare(strict_types=1);

namespace wishthis;

use PHPUnit\Framework\Attributes\{DataProvider, Depends};
use PHPUnit\Framework\TestCase;

/**
 * @covers             Wishlist
 * @coversDefaultClass \wishthis\Wishlist
 */
final class WishlistTest extends TestCase
{
    public static function setUpBeforeClass(): void
    {
        require './src/classes/wishthis/wishthis.php';

        wishthis::initialise();
    }

    /**
     * Create Wishlist
     */
    public function testCreateWishlist(): int
    {
        $result = Wishlist::create('My hopes and dreams', 1);

        $this->assertIsInt($result);

        return $result;
    }

    /**
     * Rename Wishlist
     *
     * @depends testCreateWishlist
     */
    public function testRenameWishlist(int $createWishlistResult): void
    {
        $successful = Wishlist::rename('A more realistic list', $createWishlistResult);

        $this->assertTrue($successful);
    }

    /**
     * Delete wishlist
     *
     * @depends testCreateWishlist
     */
    public function testDeleteWishlist(int $createWishlistResult): void
    {
        $successful = Wishlist::delete($createWishlistResult);

        $this->assertTrue($successful);
    }
}

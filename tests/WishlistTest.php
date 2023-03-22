<?php

declare(strict_types=1);

namespace wishthis;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class WishlistTest extends TestCase
{
    public static function setUpBeforeClass(): void
    {
        require './src/classes/wishthis/wishthis.php';

        wishthis::initialise();
    }

    public static function userIDs(): array
    {
        return array(
            'user_id = 1' => array(1, true),
            'user_id = 2' => array(2, true),
            'user_id = 3' => array(3, false),
            'user_id = 4' => array(4, true),
            'user_id = 5' => array(5, true),
        );
    }

    #[DataProvider('userIDs')]
    public function testCreateWishlist(int $user_id, bool $expected_result): void
    {
        $database    = Wishthis::getDatabase();
        $wishlist_id = Wishlist::create($database, 'My hopes and dreams', $user_id);
        $successful  = is_int($wishlist_id);

        $this->assertSame($successful, $expected_result);
    }
}

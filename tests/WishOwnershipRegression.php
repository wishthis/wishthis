<?php
// Standalone SQL regression: php tests/WishOwnershipRegression.php
require __DIR__ . '/../src/classes/wishthis/User.php';
class OwnershipUser extends wishthis\User {
    public function __construct(int $id, private bool $loggedIn = true) {
        $property = new ReflectionProperty(wishthis\User::class, 'id');
        $property->setAccessible(true);
        $property->setValue($this, $id);
    }
    public function isLoggedIn(): bool { return $this->loggedIn; }
}
$database = new class {
    public PDO $pdo;
    public function __construct() {
        $this->pdo = new PDO('sqlite::memory:');
        $this->pdo->exec('CREATE TABLE wishlists (id INT, user INT); CREATE TABLE wishes (id INT, wishlist INT)');
        $this->pdo->exec('INSERT INTO wishlists VALUES (10,1),(20,2); INSERT INTO wishes VALUES (100,10),(200,20)');
    }
    public function query($sql, $params) {
        $s = $this->pdo->prepare($sql); $s->execute($params); return $s;
    }
};
$user = new OwnershipUser(1);
foreach ([[100,10,true], [200,10,false], [200,20,false], [100,20,false], [999,10,false]] as [$wish,$list,$expected]) {
    if ($user->ownsWishInWishlist($wish,$list) !== $expected) {
        throw new RuntimeException("Wrong permission for wish $wish, list $list");
    }
}
if ((new OwnershipUser(1, false))->ownsWishInWishlist(100,10)) { throw new RuntimeException('Guest authorized'); }
echo "PASS wish ownership: owner, foreign IDs, mismatched IDs, missing wish, guest\n";

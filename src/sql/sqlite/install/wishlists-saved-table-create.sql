CREATE TABLE wishlists_saved (
    id       INTEGER PRIMARY KEY AUTOINCREMENT,
    user     INTEGER NOT NULL,
    wishlist INTEGER NOT NULL,
    FOREIGN KEY (user) REFERENCES users(id) ON DELETE CASCADE
);

CREATE INDEX idx_wishlist ON wishlists_saved(wishlist);

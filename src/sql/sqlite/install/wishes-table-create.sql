CREATE TABLE wishes (
    id             INTEGER   PRIMARY KEY AUTOINCREMENT,
    wishlist       INTEGER   NOT NULL,
    title          TEXT               DEFAULT NULL,
    description    TEXT               DEFAULT NULL,
    image          TEXT               DEFAULT NULL,
    url            TEXT               DEFAULT NULL,
    priority       INTEGER            DEFAULT NULL,
    status         TEXT               DEFAULT NULL,
    is_purchasable INTEGER   NOT NULL DEFAULT 0,
    edited         TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (wishlist) REFERENCES wishlists(id) ON DELETE CASCADE
);

CREATE INDEX idx_url ON wishes(url);

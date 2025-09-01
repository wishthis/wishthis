CREATE TABLE wishlists (
    id                 INTEGER   PRIMARY KEY AUTOINCREMENT,
    user               INTEGER   NOT NULL,
    name               TEXT      NOT NULL,
    hash               TEXT      NOT NULL,
    notification_sent  TIMESTAMP DEFAULT NULL,
    FOREIGN KEY (user) REFERENCES users(id) ON DELETE CASCADE
);

CREATE INDEX idx_hash ON wishlists(hash);

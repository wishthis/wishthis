CREATE TABLE users (
    id                         INTEGER           PRIMARY KEY AUTOINCREMENT,
    email                      TEXT     NOT NULL UNIQUE,
    password                   TEXT     NOT NULL,
    password_reset_token       TEXT              DEFAULT NULL,
    password_reset_valid_until DATETIME NOT NULL DEFAULT (datetime('now')),
    last_login                 DATETIME NOT NULL DEFAULT (datetime('now')),
    power                      INTEGER  NOT NULL DEFAULT 1,
    birthdate                  TEXT              DEFAULT NULL,
    language                   TEXT     NOT NULL DEFAULT '{{DEFAULT_LOCALE}}',
    currency                   TEXT     NOT NULL DEFAULT '{{CURRENCY_ISO}}',
    name_first                 TEXT              DEFAULT NULL,
    name_last                  TEXT              DEFAULT NULL,
    name_nick                  TEXT              DEFAULT NULL,
    channel                    TEXT              DEFAULT NULL,
    advertisements             INTEGER  NOT NULL DEFAULT 0
);

CREATE INDEX idx_password ON users(password);

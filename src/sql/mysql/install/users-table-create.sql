CREATE TABLE `users` (
    `id`                         INT          PRIMARY KEY AUTO_INCREMENT,
    `email`                      VARCHAR(64)  NOT NULL UNIQUE,
    `password`                   VARCHAR(128) NOT NULL,
    `password_reset_token`       VARCHAR(128) NULL     DEFAULT NULL,
    `password_reset_valid_until` DATETIME     NOT NULL DEFAULT NOW(),
    `last_login`                 DATETIME     NOT NULL DEFAULT NOW(),
    `power`                      INT          NOT NULL DEFAULT 1,
    `birthdate`                  DATE         NULL     DEFAULT NULL,
    `language`                   VARCHAR(6)   NOT NULL DEFAULT '{{DEFAULT_LOCALE}}',
    `currency`                   VARCHAR(3)   NOT NULL DEFAULT '{{CURRENCY_ISO}}',
    `name_first`                 VARCHAR(32)  NULL     DEFAULT NULL,
    `name_last`                  VARCHAR(32)  NULL     DEFAULT NULL,
    `name_nick`                  VARCHAR(32)  NULL     DEFAULT NULL,
    `channel`                    VARCHAR(24)  NULL     DEFAULT NULL,
    `advertisements`             TINYINT(1)   NOT NULL DEFAULT 0,

    INDEX `idx_password` (`password`)
);

/**
 * Sessions
 */
TRUNCATE `sessions`;

/**
 * Users
 */
ALTER TABLE
    `users` CHANGE COLUMN `locale` `language` VARCHAR(5) NOT NULL DEFAULT 'en_GB'
AFTER
    `birthdate`;

ALTER TABLE
    `users`
ADD
    COLUMN `advertisements` TINYINT(1) NOT NULL DEFAULT 0,
ADD
    COLUMN `currency` VARCHAR(3) NOT NULL DEFAULT 'EUR'
AFTER
    `language`;

UPDATE
    `users`
SET
    `power` = 1
WHERE
    `power` = 0;

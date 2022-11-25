/**
 * Sessions
 */
TRUNCATE `sessions`;

/**
 * Users
 */
ALTER TABLE
    `users`
ADD
    COLUMN `advertisements` TINYINT(1) NOT NULL DEFAULT 0;

/**
 * Sessions
 */
TRUNCATE `sessions`;

/**
 * Users
 */
ALTER TABLE
    `users` CHANGE COLUMN `password_reset_token` `password_reset_token` VARCHAR(40) NULL DEFAULT NULL;

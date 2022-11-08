/**
 * Sessions
 */
ALTER TABLE
    `sessions`
ADD
    COLUMN `expires` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP()
AFTER
    `session`;

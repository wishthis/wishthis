/**
 * Options
 */
UPDATE
    `options`
SET
    `key` = 'mjml_api_application_id'
WHERE
    `key` = 'mjml_api_key';

UPDATE
    `options`
SET
    `key` = 'mjml_api_secret_key'
WHERE
    `key` = 'mjml_api_secret';

/**
 * Sessions
 */
ALTER TABLE
    `sessions`
ADD
    COLUMN `expires` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP()
AFTER
    `session`;

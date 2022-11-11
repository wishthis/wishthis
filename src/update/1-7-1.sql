/**
 * Options
 */
INSERT INTO
    `options` (`key`, `value`)
VALUES
    ('api_token', UUID());

/**
 * Sessions
 */
TRUNCATE `sessions`;

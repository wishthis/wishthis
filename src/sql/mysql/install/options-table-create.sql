CREATE TABLE `options` (
    `id`    INT          PRIMARY KEY AUTO_INCREMENT,
    `key`   VARCHAR(64)  NOT NULL UNIQUE,
    `value` VARCHAR(128) NOT NULL
);

CREATE TABLE `wishlists` (
    `id`                INT          PRIMARY KEY AUTO_INCREMENT,
    `user`              INT          NOT NULL,
    `name`              VARCHAR(128) NOT NULL,
    `hash`              VARCHAR(128) NOT NULL,
    `notification_sent` TIMESTAMP        NULL DEFAULT NULL,

    INDEX `idx_hash` (`hash`),
    CONSTRAINT `FK_wishlists_user` FOREIGN KEY (`user`) REFERENCES `users` (`id`) ON DELETE CASCADE
);

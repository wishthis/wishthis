CREATE TABLE `wishes` (
    `id`             INT          NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `wishlist`       INT          NOT NULL,
    `title`          VARCHAR(128) NULL     DEFAULT NULL,
    `description`    TEXT         NULL     DEFAULT NULL,
    `image`          TEXT         NULL     DEFAULT NULL,
    `url`            VARCHAR(255) NULL     DEFAULT NULL,
    `priority`       TINYINT(1)   NULL     DEFAULT NULL,
    `status`         VARCHAR(32)  NULL     DEFAULT NULL,
    `is_purchasable` BOOLEAN      NOT NULL DEFAULT FALSE,
    `edited`         TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    INDEX `idx_url` (`url`),
    CONSTRAINT `FK_wishes_wishlists` FOREIGN KEY (`wishlist`) REFERENCES `wishlists` (`id`) ON DELETE CASCADE
);

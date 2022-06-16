/**
 * Wishlists
 */
ALTER TABLE `wishlists` ADD COLUMN `notification_sent` TIMESTAMP NULL DEFAULT NULL;

/**
 * Wishes
 */
ALTER TABLE `wishes` MODIFY     `image`  TEXT          NULL DEFAULT NULL,
                     ADD COLUMN `edited` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP;

/**
 * Products
 */
CREATE TABLE `products` (
             `wish`  INT   NOT NULL PRIMARY KEY,
             `price` FLOAT NULL     DEFAULT NULL,
    FOREIGN KEY (`wish`)
        REFERENCES `wishes` (`id`)
        ON DELETE CASCADE
);

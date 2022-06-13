/**
 * Products
 */
CREATE TABLE `products` (
             `id`    INT   PRIMARY KEY AUTO_INCREMENT,
             `wish`  INT   NOT NULL,
             `price` FLOAT NOT NULL DEFAULT 0,
FOREIGN KEY (`wish`)
    REFERENCES `wishes` (`id`)
    ON DELETE CASCADE
);
CREATE INDEX `idx_wish` ON `products` (`wish`);

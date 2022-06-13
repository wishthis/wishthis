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

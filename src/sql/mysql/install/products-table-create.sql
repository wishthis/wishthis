CREATE TABLE `products` (
    `wish`  INT   NOT NULL PRIMARY KEY,
    `price` FLOAT NULL     DEFAULT NULL,

    CONSTRAINT `FK_products_wishes` FOREIGN KEY (`wish`) REFERENCES `wishes` (`id`) ON DELETE CASCADE
);

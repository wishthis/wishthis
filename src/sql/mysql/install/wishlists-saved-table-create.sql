CREATE TABLE `wishlists_saved` (
    `id`       INT PRIMARY KEY AUTO_INCREMENT,
    `user`     INT NOT NULL,
    `wishlist` INT NOT NULL,

    INDEX `idx_wishlist` (`wishlist`),
    CONSTRAINT `FK_wishlists_saved_user` FOREIGN KEY (`user`) REFERENCES `users` (`id`) ON DELETE CASCADE
);

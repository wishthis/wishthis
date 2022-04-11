CREATE TABLE `wishlists_saved` (
             `id`       INT PRIMARY KEY AUTO_INCREMENT,
             `user`     INT NOT NULL,
             `wishlist` INT NOT NULL,
             FOREIGN KEY (`user`)
                 REFERENCES `users` (`id`)
                 ON DELETE CASCADE
);

CREATE INDEX `idx_wishlist` ON `wishlists_saved` (`wishlist`);

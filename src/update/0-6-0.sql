/**
 * Saved Wishlists
 */
CREATE TABLE `wishlists_saved` (
             `id`       INT PRIMARY KEY AUTO_INCREMENT,
             `user`     INT NOT NULL,
             `wishlist` INT NOT NULL,
             FOREIGN KEY (`user`)
                 REFERENCES `users` (`id`)
                 ON DELETE CASCADE
);

CREATE INDEX `idx_wishlist` ON `wishlists_saved` (`wishlist`);

/**
 * Wishes
 */
ALTER TABLE `wishes`
  ADD COLUMN `is_purchasable` BOOLEAN NOT NULL DEFAULT FALSE
;

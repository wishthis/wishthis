CREATE TABLE `sessions` (
    `id`      INT         NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `user`    INT         NOT NULL,
    `session` VARCHAR(60) NOT NULL,
    `expires` TIMESTAMP   NOT NULL DEFAULT CURRENT_TIMESTAMP(),

    INDEX `idx_user` (`session`),
    CONSTRAINT `FK_sessions_users` FOREIGN KEY (`user`) REFERENCES `users` (`id`) ON DELETE CASCADE
);

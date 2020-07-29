DROP TABLE IF EXISTS `page_visits`;
CREATE TABLE `page_visits`
(
    `id`         int(10) unsigned NOT NULL AUTO_INCREMENT,
    `page`       varchar(100)     NOT NULL,
    `user_id`    int(10) unsigned          DEFAULT NULL,
    `ip_address` varchar(15)      NOT NULL,
    `device`     varchar(255)     NOT NULL,
    `visit_time` timestamp        NOT NULL DEFAULT current_timestamp(),
    PRIMARY KEY (`id`),
    KEY `page_visits_FK` (`user_id`),
    CONSTRAINT `page_visits_FK` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8;

DROP TABLE IF EXISTS `user_logins`;
CREATE TABLE `user_logins`
(
    `id`          int(10) unsigned NOT NULL AUTO_INCREMENT,
    `user_id`     int(10) unsigned NOT NULL,
    `ip_address`  varchar(15)      NOT NULL,
    `device`      varchar(255)     NOT NULL,
    `login_time`  timestamp        NOT NULL DEFAULT current_timestamp(),
    `logout_time` timestamp        NULL     DEFAULT NULL ON UPDATE current_timestamp(),
    PRIMARY KEY (`id`),
    KEY `user_logins_FK` (`user_id`),
    CONSTRAINT `user_logins_FK` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8;

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users`
(
    `id`         int(10) unsigned NOT NULL AUTO_INCREMENT,
    `username`   varchar(50)      NOT NULL,
    `password`   varchar(255)              DEFAULT NULL,
    `updated_on` timestamp        NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
    `created_on` timestamp        NOT NULL DEFAULT current_timestamp(),
    PRIMARY KEY (`id`),
    UNIQUE KEY `username` (`username`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8;
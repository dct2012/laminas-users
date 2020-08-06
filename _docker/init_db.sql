DROP TABLE IF EXISTS `admin_logins`;
CREATE TABLE `admin_logins`
(
    `id`       int(10) unsigned NOT NULL AUTO_INCREMENT,
    `admin_id` int(10) unsigned NOT NULL,
    `login_id` int(10) unsigned NOT NULL,
    PRIMARY KEY (`id`),
    KEY `admin_logins_login_id_FK` (`login_id`),
    KEY `admin_logins_admin_id_FK` (`admin_id`),
    CONSTRAINT `admin_logins_admin_id_FK` FOREIGN KEY (`admin_id`) REFERENCES `admins` (`id`) ON DELETE CASCADE,
    CONSTRAINT `admin_logins_login_id_FK` FOREIGN KEY (`login_id`) REFERENCES `logins` (`id`) ON DELETE CASCADE
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8;

DROP TABLE IF EXISTS `admin_page_visits`;
CREATE TABLE `admin_page_visits`
(
    `id`            int(10) unsigned NOT NULL AUTO_INCREMENT,
    `admin_id`      int(10) unsigned NOT NULL,
    `page_visit_id` int(10) unsigned NOT NULL,
    PRIMARY KEY (`id`),
    KEY `admin_page_visits_admin_id_FK` (`admin_id`),
    KEY `admin_page_visits_page_visit_id_FK` (`page_visit_id`),
    CONSTRAINT `admin_page_visits_admin_id_FK` FOREIGN KEY (`admin_id`) REFERENCES `admins` (`id`),
    CONSTRAINT `admin_page_visits_page_visit_id_FK` FOREIGN KEY (`page_visit_id`) REFERENCES `page_visits` (`id`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8;

DROP TABLE IF EXISTS `admins`;
CREATE TABLE `admins`
(
    `id`          int(10) unsigned NOT NULL AUTO_INCREMENT,
    `identity_id` int(10) unsigned NOT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `admins_identity_id_UN` (`identity_id`),
    CONSTRAINT `admins_identity_id_FK` FOREIGN KEY (`identity_id`) REFERENCES `identities` (`id`) ON DELETE CASCADE
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8;

DROP TABLE IF EXISTS `identities`;
CREATE TABLE `identities`
(
    `id`         int(10) unsigned NOT NULL AUTO_INCREMENT,
    `name`       varchar(100)     NOT NULL,
    `password`   varchar(255)     NOT NULL,
    `updated_on` timestamp        NULL     DEFAULT NULL ON UPDATE current_timestamp(),
    `created_on` timestamp        NOT NULL DEFAULT current_timestamp(),
    PRIMARY KEY (`id`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8;

DROP TABLE IF EXISTS `logins`;
CREATE TABLE `logins`
(
    `id`          int(10) unsigned NOT NULL AUTO_INCREMENT,
    `ip_address`  varchar(15)      NOT NULL,
    `user_agent`  varchar(255)     NOT NULL,
    `login_time`  timestamp        NOT NULL DEFAULT current_timestamp(),
    `logout_time` timestamp        NULL     DEFAULT NULL ON UPDATE current_timestamp(),
    PRIMARY KEY (`id`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8;

DROP TABLE IF EXISTS `page_visits`;
CREATE TABLE `page_visits`
(
    `id`         int(10) unsigned NOT NULL AUTO_INCREMENT,
    `page`       varchar(100)     NOT NULL,
    `ip_address` varchar(15)      NOT NULL,
    `user_agent` varchar(255)     NOT NULL,
    `visit_time` timestamp        NOT NULL DEFAULT current_timestamp(),
    PRIMARY KEY (`id`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8;

DROP TABLE IF EXISTS `user_logins`;
CREATE TABLE `user_logins`
(
    `id`       int(10) unsigned NOT NULL AUTO_INCREMENT,
    `user_id`  int(10) unsigned NOT NULL,
    `login_id` int(10) unsigned NOT NULL,
    PRIMARY KEY (`id`),
    KEY `user_logins_login_id_FK` (`login_id`),
    KEY `user_logins_user_id_FK` (`user_id`),
    CONSTRAINT `user_logins_login_id_FK` FOREIGN KEY (`login_id`) REFERENCES `logins` (`id`) ON DELETE CASCADE,
    CONSTRAINT `user_logins_user_id_FK` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8;

DROP TABLE IF EXISTS `user_page_visits`;
CREATE TABLE `user_page_visits`
(
    `id`            int(10) unsigned NOT NULL AUTO_INCREMENT,
    `user_id`       int(10) unsigned NOT NULL,
    `page_visit_id` int(10) unsigned NOT NULL,
    PRIMARY KEY (`id`),
    KEY `user_page_visits_user_id_FK` (`user_id`),
    KEY `user_page_visits_page_visit_id_FK` (`page_visit_id`),
    CONSTRAINT `user_page_visits_page_visit_id_FK` FOREIGN KEY (`page_visit_id`) REFERENCES `page_visits` (`id`),
    CONSTRAINT `user_page_visits_user_id_FK` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8;

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users`
(
    `id`          int(10) unsigned NOT NULL AUTO_INCREMENT,
    `identity_id` int(10) unsigned NOT NULL,
    PRIMARY KEY (`id`),
    KEY `users_identity_id_FK` (`identity_id`),
    CONSTRAINT `users_identity_id_FK` FOREIGN KEY (`identity_id`) REFERENCES `identities` (`id`) ON DELETE CASCADE
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8;
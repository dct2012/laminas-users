ALTER TABLE docker.users
    MODIFY COLUMN id INT UNSIGNED auto_increment NOT NULL;

CREATE TABLE docker.user_logins
(
    id         INT UNSIGNED auto_increment      NOT NULL,
    user_id    INT UNSIGNED                     NOT NULL,
    ip_address varchar(100)                     NOT NULL,
    device     varchar(100)                     NOT NULL,
    login_time time DEFAULT current_timestamp() NOT NULL,
    CONSTRAINT user_logins_PK PRIMARY KEY (id),
    CONSTRAINT user_logins_FK FOREIGN KEY (user_id) REFERENCES docker.users (id) ON DELETE CASCADE
)
    ENGINE = InnoDB
    DEFAULT CHARSET = utf8
    COLLATE = utf8_general_ci;
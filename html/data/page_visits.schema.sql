CREATE TABLE docker.page_visits
(
    id         INT UNSIGNED auto_increment              NOT NULL,
    page       varchar(100)                             NOT NULL,
    user_id    INT UNSIGNED DEFAULT NULL                NULL,
    ip_address varchar(15)                              NOT NULL,
    device     varchar(255)                             NOT NULL,
    visit_time TIMESTAMP    DEFAULT current_timestamp() NOT NULL,
    CONSTRAINT page_visits_PK PRIMARY KEY (id),
    CONSTRAINT page_visits_FK FOREIGN KEY (user_id) REFERENCES docker.users (id) ON DELETE SET NULL
)
    ENGINE = InnoDB
    DEFAULT CHARSET = utf8
    COLLATE = utf8_general_ci;

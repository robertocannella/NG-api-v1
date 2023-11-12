
CREATE DATABASE api_db;

GRANT ALL PRIVILEGES ON api_db.* TO 'ng_api_db_user'@'localhost';

CREATE TABLE task (
    id INT NOT NULL AUTO_INCREMENT,
    name VARCHAR(128) NOT NULL,
    priority INT DEFAULT NULL,
    is_completed BOOLEAN NOT NULL DEFAULT FALSE,
    PRIMARY KEY (id),
    INDEX (name) );

CREATE TABLE user (
    id INT NOT NULL AUTO_INCREMENT,
    name VARCHAR(128) NOT NULL,
    username VARCHAR(128) NOT NULL,
    api_key VARCHAR(32) NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    PRIMARY KEY (id), UNIQUE (username),
    UNIQUE (api_key) );

ALTER TABLE task
    ADD user_id
    INT NOT NULL, ADD INDEX (user_id);

ALTER TABLE task
    ADD FOREIGN KEY (user_id)
    REFERENCES user(id)
    ON DELETE CASCADE ON UPDATE CASCADE;


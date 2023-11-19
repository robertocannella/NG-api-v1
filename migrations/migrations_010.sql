
CREATE DATABASE api_db;

CREATE USER 'api_db_user'@'localhost' IDENTIFIED BY 'password';

GRANT ALL PRIVILEGES ON api_db.* TO 'api_db_user'@'localhost';

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

CREATE TABLE refresh_token (
    token_hash VARCHAR(64) NOT NULL,
    expires_at BIGINT UNSIGNED NOT NULL,
    PRIMARY KEY (token_hash),
    INDEX (expires_at)
);

INSERT INTO refresh_token (token_hash, expires_at) VALUES ("ABC123", 1000);


-- FOR SESSIONS
CREATE DATABASE IF NOT EXISTS persistent COLLATE utf8_general_ci; -- CASE-INSENSITIVE
CREATE USER 'sess_admin'@'localhost' IDENTIFIED BY 'secret';
GRANT SELECT, INSERT, UPDATE, DELETE ON persistent.* TO 'sess_admin'@'localhost';

USE persistent;
CREATE TABLE IF NOT EXISTS users (
    user_key CHAR(8) NOT NULL PRIMARY KEY,
    username CHAR(30) NOT NULL UNIQUE,
    pwd VARCHAR(255) NOT NULL
) ENGINE = InnoDB DEFAULT CHARSET=utf8; -- InnoDB is for Transaction Support

CREATE TABLE IF NOT EXISTS sessions (
    sid VARCHAR(40) NOT NULL PRIMARY KEY,
    expiry BIGINT(20) UNSIGNED NOT NULL,
    data TEXT NOT NULL
)ENGINE = InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS autologin (
    user_key CHAR(8) NOT NULL,
    token CHAR(32) NOT NULL,
    created TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP(),
    data TEXT,
    used TINYINT(1) NOT NULL DEFAULT 0,
    PRIMARY KEY (user_key, token)
)ENGINE = InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE users MODIFY COLUMN user_key VARCHAR(12) NOT NULL;
ALTER TABLE autologin MODIFY COLUMN user_key VARCHAR(12) NOT NULL;
ALTER TABLE sessions ADD COLUMN user_key VARCHAR(12);
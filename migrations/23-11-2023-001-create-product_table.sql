CREATE TABLE product (
                         id INT NOT NULL UNIQUE ,
                         name VARCHAR(128) NOT NULL,
                         description TEXT NULL DEFAULT NULL,
                         PRIMARY KEY (id)
);

CREATE USER 'product_db_user'@'localhost' IDENTIFIED BY 'password';

GRANT ALL PRIVILEGES ON product.* TO 'product_db_user'@'localhost';

INSERT INTO product (id, name, description)
VALUES (123,'Product One', 'This is product one'),
       (345,'Second Product', 'A second product here'),
       (128,'Product #3', ''),
       (423,'The 4th One', 'Some <b>HTML</b> in the description');

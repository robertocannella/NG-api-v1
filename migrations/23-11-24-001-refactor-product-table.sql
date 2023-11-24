ALTER TABLE product CHANGE id product_id INT;

ALTER TABLE product DROP product_id;

ALTER TABLE product ADD id INT NOT NULL AUTO_INCREMENT PRIMARY KEY ;

ALTER TABLE product ADD product_id INT NOT NULL;






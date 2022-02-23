USE product;

SELECT `product`.`sku`, `product`.`product_name`, `product`.`price`, `attribute`.`attribute_name`, `size_value`.`size_value`, `weight_value`.`weight_value`, `dimension_option`.`dimension_option`, `dimension_value`.`dimension_value` 
    FROM `product` 
    LEFT JOIN `product_attribute` 
    ON `product`.`sku` = `product_attribute`.`sku` 
    LEFT JOIN `attribute` 
    ON `product_attribute`.`attribute_id`=`attribute`.`attribute_id` 
    LEFT JOIN `size_value` 
    ON (`product`.`sku`=`size_value`.`sku` 
    AND `product_attribute`.`attribute_id`=`size_value`.`attribute_id`) 
    LEFT JOIN `weight_value`
    ON (`weight_value`.`attribute_id`=`product_attribute`.`attribute_id` 
    AND `weight_value`.`sku`=`product_attribute`.`sku`) 
    LEFT JOIN `dimension_value` 
    ON (`dimension_value`.`attribute_id`=`product_attribute`.`attribute_id` 
    AND `dimension_value`.`sku`=`product_attribute`.`sku`) 
    LEFT JOIN `dimension_option` 
    ON `dimension_option`.`dimension_id` = `dimension_value`.`dimension_id`
    ORDER BY `product`.`sku`;

CREATE TABLE IF NOT EXISTS `product` (
    sku VARCHAR(30) NOT NULL PRIMARY KEY,
    product_name VARCHAR(30) NOT NULL,
    price DECIMAL(10 , 2 ) NOT NULL
);

INSERT IGNORE INTO `product`
VALUES ('333cca', 'bbb', 25.75);

CREATE TABLE IF NOT EXISTS `attribute` (
    attribute_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    attribute_name VARCHAR(30) NOT NULL UNIQUE
);

INSERT IGNORE INTO `attribute` (attribute_name)
VALUES ('size'), ('weight'), ('dimension');

CREATE TABLE IF NOT EXISTS `product_attribute` (
    sku VARCHAR(30) NOT NULL,
    attribute_id INT NOT NULL,
    CONSTRAINT sku_attribute_id UNIQUE (sku , attribute_id),
    FOREIGN KEY (sku)
        REFERENCES `product` (sku)
        ON DELETE CASCADE,
    FOREIGN KEY (attribute_id)
        REFERENCES `attribute` (attribute_id)
);

INSERT IGNORE INTO `product_attribute`
VALUES 
('333bba', 1), 
('333bba', 3), 
('333cca', 2);

CREATE TABLE IF NOT EXISTS `size_value` (
    attribute_id INT DEFAULT 1,
    sku VARCHAR(30) NOT NULL PRIMARY KEY,
    size_value DECIMAL(10 , 2 ) NOT NULL,
    FOREIGN KEY (attribute_id)
        REFERENCES `attribute` (attribute_id),
    FOREIGN KEY (sku)
        REFERENCES `product` (sku)
        ON DELETE CASCADE
);

INSERT IGNORE INTO `size_value` (sku, size_value)
VALUES ('333bba', 22.4);

CREATE TABLE IF NOT EXISTS `weight_value` (
    attribute_id INT DEFAULT 2,
    sku VARCHAR(30) NOT NULL PRIMARY KEY,
    weight_value DECIMAL(10 , 2 ) NOT NULL,
    FOREIGN KEY (attribute_id)
        REFERENCES `attribute` (attribute_id),
    FOREIGN KEY (sku)
        REFERENCES `product` (sku)
        ON DELETE CASCADE
);

INSERT IGNORE INTO `weight_value` (sku, weight_value)
VALUES ('333cca', 2.37);

CREATE TABLE IF NOT EXISTS `dimension_option` (
    dimension_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY UNIQUE,
    dimension_option VARCHAR(30) NOT NULL UNIQUE
);

INSERT IGNORE INTO `dimension_option` (dimension_option)
VALUES ('height'), ('width'), ('length');

CREATE TABLE IF NOT EXISTS `dimension_value` (
    attribute_id INT DEFAULT 3,
    sku VARCHAR(30) NOT NULL,
    dimension_id INT NOT NULL,
    dimension_value DECIMAL(10 , 2 ) NOT NULL,
    FOREIGN KEY (attribute_id)
        REFERENCES `attribute` (attribute_id),
    FOREIGN KEY (sku)
        REFERENCES `product` (sku)
        ON DELETE CASCADE,
    FOREIGN KEY (dimension_id)
        REFERENCES `dimension_option` (dimension_id),
    UNIQUE KEY (sku , dimension_id)
);

INSERT IGNORE INTO `dimension_value` (sku, dimension_id, dimension_value) 
VALUES ('333bba', 1, 8.77), ('333bba', 2, 5), ('333bba', 3, 5);

  

CREATE TABLE IF NOT EXISTS `%prefix%woo_columns` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `columns_name` VARCHAR(128) NULL DEFAULT NULL,
  `columns_nice_name` VARCHAR(128) NULL DEFAULT NULL,
	PRIMARY KEY (`id`)
)
DEFAULT CHARSET=utf8
;
INSERT INTO `%prefix%woo_columns` (id, columns_name, columns_nice_name) VALUES
(NULL, 'id', 'ID'),
(NULL, 'product_title', 'Name'),
(NULL, 'sku', 'SKU'),
(NULL, 'thumbnail', 'Thumbnail'),
(NULL, 'categories', 'Categories'),
(NULL, 'price', 'Price'),
(NULL, 'attribute', 'Attribute'),
(NULL, 'description', 'Summary'),
(NULL, 'add_to_cart', 'Buy'),
(NULL, 'reviews', 'Reviews'),
(NULL, 'date', 'Date');

ALTER TABLE `%prefix%tables`
    ADD COLUMN `woo_settings` TEXT NULL AFTER `settings`;
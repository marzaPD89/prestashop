<?php

$sql[] = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'videodesk_call` (
`id_videodesk_call` int(5) NOT NULL AUTO_INCREMENT, 
`id_shop` int(5) NOT NULL, 
`id_customer` INT (10) NULL,
`id_cart` INT (10) NULL,
`id_employee` INT (10) NULL,
`call_date` datetime NOT NULL,
`call_type` VARCHAR (50) NOT NULL,
`connexion_page` VARCHAR (300) NOT NULL,
`call_transcript` TEXT NULL,
`date_add` datetime NULL, 
`date_upd` datetime NULL,
PRIMARY KEY(`id_videodesk_call`))
COLLATE="utf8_unicode_ci"
ENGINE=MyISAM;';

$sql[] = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'videodesk_historic_log` (
`id_videodesk_historic_log` int(5) NOT NULL AUTO_INCREMENT,
`id_shop` int(5) NOT NULL,
`filename` VARCHAR (50) NOT NULL,
`size` INT (10) NULL,
`date_add` datetime NULL,
`date_upd` datetime NULL,
PRIMARY KEY(`id_videodesk_historic_log`))
COLLATE="utf8_unicode_ci"
ENGINE=MyISAM;';

$sql[] = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'videodesk_shop_configuration` (
`id_shop` int(10) NOT NULL,
`website_id` text NOT NULL,
`displayed` tinyint(1) NOT NULL DEFAULT "0",
`progress_criterias` tinyint(1) NOT NULL DEFAULT "0",
`progress_colors` tinyint(1) NOT NULL DEFAULT "0",
`progress_texts` tinyint(1) NOT NULL DEFAULT "0",
`progress_messages` tinyint(1) NOT NULL DEFAULT "0",
`progress_agent` tinyint(1) NOT NULL DEFAULT "0",
`progressbar_criterias` tinyint(1) NOT NULL DEFAULT "0",
`display_for_all` TINYINT(1) NOT NULL DEFAULT "1",
`display_ips` TEXT NULL,
`criterias` TINYINT(1) NOT NULL DEFAULT "0",
`criterias_all_conditions` TINYINT(1) NOT NULL DEFAULT "0",
`scope` TINYINT(1) NOT NULL DEFAULT "0",
`ftp_active` tinyint(1) DEFAULT "0",
`ftp_host` varchar(200) DEFAULT NULL,
`ftp_login` varchar(200) DEFAULT NULL,
`ftp_password` varchar(200) DEFAULT NULL,
`ftp_dir` varchar(200) DEFAULT NULL,
`track_stats` TINYINT(1) NOT NULL DEFAULT 1,
PRIMARY KEY (`id_shop`))
COLLATE="utf8_unicode_ci"
ENGINE=MyISAM;';

$sql[] = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'videodesk_shop_configuration_criteria` (
`id_criteria` INT(10) NOT NULL AUTO_INCREMENT,
`name` TINYTEXT NULL,
`with_value` TINYINT NOT NULL DEFAULT "0",
PRIMARY KEY (`id_criteria`))
COLLATE="utf8_unicode_ci"
ENGINE=MyISAM;';

$sql[] = 'INSERT INTO `'._DB_PREFIX_.'videodesk_shop_configuration_criteria` (`id_criteria`, `name`, `with_value`) VALUES
(1, "seconds", 1),
(2, "cart_amount", 1),
(3, "product_cart_amount", 1),
(4, "visitor_logged", 0),
(5, "customer_nb_returning", 1);';

$sql[] = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'videodesk_shop_configuration_criteria_value` (
`id_shop` INT(10) NOT NULL,
`id_criteria` INT(10) NOT NULL,
`active` TINYINT(1) NOT NULL DEFAULT "0",
`value` TEXT NULL DEFAULT NULL,
PRIMARY KEY (`id_shop`, `id_criteria`))
COLLATE="utf8_unicode_ci"
ENGINE=MyISAM;';

$sql[] = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'videodesk_shop_configuration_group_pages` (
`id_group_pages` INT(10) NOT NULL AUTO_INCREMENT,
`name` TINYTEXT NULL DEFAULT NULL,
`position` INT(11) NOT NULL,
PRIMARY KEY (`id_group_pages`))
COLLATE="utf8_unicode_ci"
ENGINE=MyISAM;';

$sql[] = 'INSERT INTO `'._DB_PREFIX_.'videodesk_shop_configuration_group_pages` (`id_group_pages`, `name`, `position`) VALUES
(1, "order", 3),
(2, "standard", 1),
(3, "account", 2),
(4, "categories", 4),
(5, "cms", 5),
(6, "modules", 6);';

$sql[] = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'videodesk_shop_configuration_page` (
`id_page` INT(10) NOT NULL AUTO_INCREMENT,
`id_group_pages` INT NOT NULL,
`name` TINYTEXT NULL DEFAULT NULL,
PRIMARY KEY (`id_page`))
COLLATE="utf8_unicode_ci"
ENGINE=MyISAM;';

$sql[] = 'INSERT INTO `'._DB_PREFIX_.'videodesk_shop_configuration_page` (`id_page`, `id_group_pages`, `name`) VALUES
(1, 1, "order"),
(2, 1, "orderopc"),
(3, 1, "orderconfirmation"),
(4, 2, "index"),
(5, 3, "authentication"),
(6, 3, "password"),
(7, 2, "bestsales"),
(8, 2, "productscomparison"),
(9, 2, "contact"),
(10, 2, "newproducts"),
(11, 2, "pricesdrop"),
(12, 2, "search"),
(13, 2, "stores"),
(14, 2, "manufacturer"),
(15, 2, "supplier"),
(16, 3, "myaccount"),
(17, 3, "history"),
(18, 3, "addresses"),
(19, 3, "identity"),
(20, 3, "discount"),
(21, 3, "orderfollow"),
(22, 3, "orderslip"),
(23, 2, "pagenotfound");';

$sql[] = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'videodesk_shop_configuration_page_value` (
`id_page` INT(10) NOT NULL,
`id_shop` INT(10) NOT NULL,
PRIMARY KEY (`id_page`, `id_shop`))
COLLATE="utf8_unicode_ci"
ENGINE=MyISAM;';
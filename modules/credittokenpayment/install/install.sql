CREATE TABLE `ps_credittokenpayment_configuration` (
	`id_row` INT NOT NULL,
	`id_product` INT NOT NULL,
	`credits` INT NOT NULL,
	PRIMARY KEY (`id_row`, `id_product`)
)
COLLATE='latin1_swedish_ci'
ENGINE=InnoDB
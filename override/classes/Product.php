<?php

class Product extends ProductCore
{
	/*
	* module: mymodcomments
	* date: 2015-05-15 15:10:26
	* version: 0.4
	*/
	public function getComments($limit_start, $limit_end = false)
	{
		$limit = (int)$limit_start;
		if ($limit_end)
			$limit = (int)$limit_start.','.(int)$limit_end;

		$comments = Db::getInstance()->executeS('
		SELECT * FROM `'._DB_PREFIX_.'mymod_comment`
		WHERE `id_product` = '.(int)$this->id.'
		ORDER BY `date_add` DESC LIMIT '.$limit);

		return $comments;
	}
}
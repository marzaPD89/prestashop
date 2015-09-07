<?php
/*
* ntur
*/

class WebserviceSpecificManagementDispo implements WebserviceSpecificManagementInterface
{
	protected $objOutput;
	protected $wsObject;
	protected $output;

	// ------------------------------------------------
	// GETTERS & SETTERS
	// ------------------------------------------------

	public function setObjectOutput(WebserviceOutputBuilderCore $obj) { $this->objOutput = $obj; return $this; }
	public function getObjectOutput() { return $this->objOutput; }
	public function setWsObject(WebserviceRequestCore $obj) { $this->wsObject = $obj; return $this; }
	public function getWsObject() { return $this->wsObject; }


	public function getContent(){
		return $this->objOutput->getObjectRender()->overrideContent($this->output);
	}

	public function manage()
	{
		global $input_xml;

		$db = Db::getInstance();
		$product_query = "";
		$combination_query = "";


		if($this->wsObject->method == 'GET'){
			$this->output ="<stock>\t
							<dispo>\t\t
							<reference maxSize=\"32\" format=\"isReference\"/>\t\t
							<id_shop format=\"isUnsignedInt\"/>\t\t
							<active format=\"isBool\"/>\t\t
							<quantity format=\"isInt\"/>\t
							</dispo>\n</stock>";
			return;
		}
			

		try
		{
			$xml = new SimpleXMLElement($input_xml);
		}
		catch (Exception $error)
		{
			$this->objOutput->setStatus(500);
			$this->output .= "<error>\n\t".$error->getMessage()."\n</error>\n<data-length>".strlen($input_xml)."</data-lenght>\n<data>\n\t".$input_xml."\n</data>";
			return;
		}

		$autocommit = (int)$db->getValue('SELECT @@autocommit');
		$unique_checks = (int)$db->getValue('SELECT @@unique_checks');
		$foreign_key_checks = (int)$db->getValue('SELECT @@foreign_key_checks');

		$db->execute('SET autocommit=0, unique_checks=0, foreign_key_checks=0;');
		$db->execute('START TRANSACTION;');

		file_put_contents('log_dispo.txt', "\nStart: ". microtime(), FILE_APPEND);
		foreach($xml->stock->dispo as $d){
			$row = $db->getRow('
				SELECT id_product , 0 as id_product_attribute
				FROM '._DB_PREFIX_.'product 
				WHERE reference = \''.pSQL($d->reference).'\'');

			if(!empty($row)){
				$db->execute('
					INSERT INTO '._DB_PREFIX_.'stock_available (id_product,id_product_attribute,id_shop,id_shop_group,quantity,out_of_stock)
					VALUES('.(int)$row['id_product'].' ,0 ,'.(int)$d->id_shop.' , 0, '.(int)$d->quantity.',2)
					ON DUPLICATE KEY UPDATE quantity='.(int)$d->quantity);

				//UPDATE '._DB_PREFIX_.'stock_available SET quantity='.(int)$d->quantity.' 
				//WHERE id_product='.(int)$row['id_product'].' AND id_product_attribute=0 AND id_shop='.(int)$d->id_shop);

				$db->execute('
					UPDATE '._DB_PREFIX_.'product_shop SET 
					active = '.(int)$d->active.' 
					WHERE id_product='.(int)$row['id_product'] . ' AND id_shop='.(int)$d->id_shop);
			}
		}

		file_put_contents('log_dispo.txt', "\nEnd: ". microtime(), FILE_APPEND);

		$db->execute('COMMIT;');
		$db->execute("SET autocommit={$autocommit}, unique_checks={$unique_checks}, foreign_key_checks={$foreign_key_checks};");
		$this->output = '<status> OK </status>'."\n";

		return $this->wsObject->getOutputEnabled();
	}
}

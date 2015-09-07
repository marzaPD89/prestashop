<?php
if (!defined('_PS_VERSION_'))
  exit;
 
class Ajaxtest extends Module
  {
  public function __construct()
    {
    $this->name = 'ajaxtest';
    $this->tab = 'Test';
    $this->version = 1.0;
    $this->author = 'Luca Marzaro';
    $this->need_instance = 0;
 
    parent::__construct();

    $this->displayName = $this->l('Ajaxtest');
    $this->description = $this->l('Prova ajax.');
    }
 
  public function install()
    {
    if ((parent::install() == false) OR (!$this->registerHook('prodTest')))
      return false;
    return true;
    }


  public function hookProdTest( $params )
  {
      global $smarty;


      return $this->display(__FILE__,'ajaxtest.tpl');
  }
}

?>

<?php
/**
 * Created by PhpStorm.
 * User: marluc
 * Date: 06/03/2015
 * Time: 12:54
 */

include_once('../../config/config.inc.php');
include_once('../../init.php');


$flagOK = Tools::getValue('delete');

var_dump(Tools::getValue('id_row'));

if($flagOK) {
    doAjax();
}


function doAjax() {

    $id_row_to_delete = Tools::getValue('id_row');
    Db::getInstance()->delete('credittokenpayment_configuration', 'id_row = '.$id_row_to_delete);

}
<?php
/**
 * Created by PhpStorm.
 * User: marluc
 * Date: 06/03/2015
 * Time: 12:54
 */

include_once('../../config/config.inc.php');
include_once('../../init.php');


$flagOK = Tools::getValue('action');

if($flagOK) {
    provaAjax();
}


function provaAjax() {

		$smarty = Context::getContext()->smarty;

        $daJsoncodare = array();

        $product = new Product(6);
        $product2 = new Product(7);
        array_push($daJsoncodare, $product);
        array_push($daJsoncodare, $product2);

        $smarty->assign('products', $daJsoncodare);

        $output = $smarty->fetch(_PS_MODULE_DIR_.'ajaxtest/views/front/template.tpl');
        echo $output;

}
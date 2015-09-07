<?php
/**
 * Created by PhpStorm.
 * User: marluc
 * Date: 06/03/2015
 * Time: 12:04
 */

include('/config/config.inc.php');



class TryAjax {

    protected function provaAjax() {

        if(Tools::getValue('action')) {

            $daJsoncodare = array();

            $product = new Product(6);
            $product2 = new Product(7);
            array_push($daJsoncodare, $product);
            array_push($daJsoncodare, $product2);

            return Tools::jsonEncode($daJsoncodare);

        }

        return false;

    }


}
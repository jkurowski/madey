<?php

class Zend_View_Helper_InvestStatus extends Zend_View_Helper_Abstract {

    public function investStatus($numer){
        switch ($numer) {
            case '1':
                return 'W sprzedaży';
            case '2':
                return 'Zakończona';
            case '3':
                return 'Planowana';
        }
    }
}
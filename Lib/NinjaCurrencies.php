<?php

/*
 * This code belongs to NIMA Software SRL | nimasoftware.com
 * For details contact contact@nimasoftware.com
 */

namespace Stev\NinjaInvoiceBundle\Lib;

/**
 * Description of NinjaCurrencies
 *
 * @author stefan
 */
final class NinjaCurrencies
{

    //we only support EUR and RON
    //for other countries check the currencies table in NinjaInvoice and complete this array
    private static $currencies = array(
        'EUR' =>
        array(
            'id' => 3,
            'name' => 'Euro',
            'symbol' => '€',
            'precision' => 2,
            'thousand_separator' => '.',
            'decimal_separator' => ',',
            'code' => 'EUR',
        ),
        'RON' =>
        array(
            'id' => 24,//since upgrading to v2.9.5 of invoice ninja
            'name' => 'Romanian New Leu',
            'symbol' => '',
            'precision' => 2,
            'thousand_separator' => ',',
            'decimal_separator' => '.',
            'code' => 'RON',
        ),
    );

    public static function getCurrencyIdByCode($currencyCode)
    {
        return isset(self::$currencies[strtoupper($currencyCode)]) ? self::$currencies[strtoupper($currencyCode)]['id'] : self::$currencies['EUR']['id']; //else return EUR
    }

}

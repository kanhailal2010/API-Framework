<?php

class calculate
{
    public static function currency_formater($value=0,$digit=0) {
        if($value >=0 && $digit >0 )
            //$currency = number_format($value,$digit);
            $currency = number_format($value,$digit);
        else
            $currency = number_format($value,2);

            return $currency;
    }
}
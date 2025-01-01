<?php

require_once('ChildrenPriceCode.php');
require_once('RegularPriceCode.php');
require_once('NewReleasePriceCode.php');
require_once('BlockbusterPriceCode.php');

class PriceCodeFactory {
    public static function create(int $priceCode): IBasePriceCode {
        switch ($priceCode) {
            case PriceCodes::CHILDRENS:
                return new ChildrenPriceCode();
            case PriceCodes::REGULAR:
                return new RegularPriceCode();
            case PriceCodes::NEW_RELEASE:
                return new NewReleasePriceCode();
            case PriceCodes::BLOCKBUSTER:
                return new BlockbusterPriceCode();
            default:
                throw new InvalidArgumentException("Invalid price code: $priceCode");
        }
    }
}
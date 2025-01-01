<?php

class ChildrenPriceCode extends BasePriceCode {
    public function __construct() {
        $this->defaultRent = 1.5;
        $this->rentPerDay = 1.5;
        $this->freeRentDays = 3;

        parent::__construct();
    }
}
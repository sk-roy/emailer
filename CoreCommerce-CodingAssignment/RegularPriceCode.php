<?php

class RegularPriceCode extends BasePriceCode {
    public function __construct() {
        $this->defaultRent = 2.0;
        $this->rentPerDay = 1.5;
        $this->freeRentDays = 2;
        
        parent::__construct();
    }
}

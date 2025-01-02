<?php
    
class BlockbusterPriceCode extends BasePriceCode {
    public function __construct() {
        $this->defaultRent = 0;
        $this->rentPerDay = 4.0;
        $this->freeRentDays = 0;
        
        parent::__construct();
    }
}
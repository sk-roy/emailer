<?php

class NewReleasePriceCode extends BasePriceCode {

    public function __construct() {
        $this->defaultRent = 0;
        $this->rentPerDay = 3.0;
        $this->freeRentDays = 0;
        
        parent::__construct();
    }

    public function getFrequentRenterPoints(int $daysRented): int {
        return $daysRented > 1 ? 2 : 1;
    }
}
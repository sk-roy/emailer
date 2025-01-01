<?php

class NewReleasePriceCode extends BasePriceCode {
    public function __construct() {
        parent::__construct(0.0, 3.0, 0);
    }

    public function getFrequentRenterPoints(int $daysRented): int {
        return $daysRented > 1 ? 2 : 1;
    }
}
<?php

require_once('IBasePriceCode.php');

class BasePriceCode implements IBasePriceCode {
    
    protected float $defaultRent;
    protected float $rentPerDay;
    protected int $freeRentDays;

    public function __construct(float $defaultRent, float $rentPerDay, int $freeRentDays) {
        $this->defaultRent = $defaultRent;
        $this->rentPerDay = $rentPerDay;
        $this->freeRentDays = $freeRentDays;
    }

    /**
     * @return float
     */
    public function getDefaultRent(): float {
        return $this->defaultRent;
    }

    /**
     * @return float
     */
    public function getRentPerDay(): float {
        return $this->rentPerDay;
    }

    /**
     * @return int
     */
    public function getFreeRentDays(): int {
        return $this->freeRentDays;
    }

    public function getAmount(int $daysRented): float {
        $thisAmount = $this->getDefaultRent();
        if ($daysRented > $this->getFreeRentDays()) {
            $thisAmount += ($daysRented - $this->getFreeRentDays()) * $this->getRentPerDay();
        }
        return $thisAmount ?? 0;
    }

    public function getFrequentRenterPoints(int $daysRented): int {
        return 1;
    }
}
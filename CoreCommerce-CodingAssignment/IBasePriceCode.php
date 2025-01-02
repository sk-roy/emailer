<?php

interface IBasePriceCode {
    public function getDefaultRent(): float;
    public function getRentPerDay(): float;
    public function getFreeRentDays(): int;

    public function getAmount(int $daysRented): float;
    public function getFrequentRenterPoints(int $daysRented): int;
}
<?php

class Rental
{
    /**
     * @var Movie
     */
    private $movie;

    /**
     * @var int
     */
    private $daysRented;

    /**
     * @var int
     */
    private $defaultRent;

    /**
     * @var int
     */
    private $rentPerDay;


    /**
     * @var int
     */
    private $freeRentPeriod;


    /**
     * @param Movie $movie
     * @param int $daysRented
     * @param int $defaultRent
     * @param int $rentPerDay
     * @param int $freeRentPeriod
     */
    public function __construct(Movie $movie, $daysRented, $defaultRent, $rentPerDay, $freeRentPeriod)
    {
        $this->movie = $movie;
        $this->daysRented = $daysRented;
        $this->defaultRent = $defaultRent;
        $this->rentPerDay = $rentPerDay;
        $this->freeRentPeriod = $freeRentPeriod;
    }

    /**
     * @return Movie
     */
    public function movie()
    {
        return $this->movie;
    }

    /**
     * @return int
     */
    public function daysRented()
    {
        return $this->daysRented;
    }

    /**
     * @return int
     */
    public function defaultRent()
    {
        return $this->defaultRent;
    }

    /**
     * @return int
     */
    public function rentPerDay()
    {
        return $this->rentPerDay;
    }

    /**
     * @return int
     */
    public function freeRentPeriod()
    {
        return $this->freeRentPeriod;
    }

    /**
     * @return int
     */
    public function getAmount() {
        $thisAmount = $this->defaultRent();
        if ($this->daysRented() > $this->freeRentPeriod()) {
            $thisAmount += ($this->daysRented() - $this->freeRentPeriod()) * $this->rentPerDay();
        }

        return $thisAmount ?? 0;
    }

    public function getFrequentRenterPoints() {
        $frequentRenterPoints = 1;
        if ($this->movie()->priceCode() === (int) PriceCodes::NEW_RELEASE && $this->daysRented() > 1) {
            $frequentRenterPoints = 2;
        }
        return $frequentRenterPoints;
    }
}

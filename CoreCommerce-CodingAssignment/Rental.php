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
    private $priceCode;


    /**
     * @param Movie $movie
     * @param int $daysRented
     * @param int $priceCode
     */
    public function __construct(Movie $movie, int $daysRented) {
        $this->movie = $movie;
        $this->daysRented = $daysRented;
        $this->priceCode = PriceCodeFactory::create($movie->priceCode());
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
     * @return float
     */
    public function getAmount(): float 
    {
        return $this->priceCode->getAmount($this->daysRented);
    }


    /**
     * @return int
     */
    public function getFrequentRenterPoints(): int 
    {
        return $this->priceCode->getFrequentRenterPoints($this->daysRented);
    }
}

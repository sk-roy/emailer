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
     * @var IBasePriceCode
     */
    private $priceCodeObj;


    /**
     * @param Movie $movie
     * @param int $daysRented
     */
    public function __construct(Movie $movie, int $daysRented) {
        $this->movie = $movie;
        $this->daysRented = $daysRented;
        $this->priceCodeObj = PriceCodeFactory::create($movie->priceCode());
    }

    /**
     * @return Movie
     */
    public function movie(): Movie
    {
        return $this->movie;
    }

    /**
     * @return int
     */
    public function daysRented(): int
    {
        return $this->daysRented;
    }


    /**
     * @return float
     */
    public function getAmount(): float 
    {
        return $this->priceCodeObj->getAmount($this->daysRented);
    }


    /**
     * @return int
     */
    public function getFrequentRenterPoints(): int 
    {
        return $this->priceCodeObj->getFrequentRenterPoints($this->daysRented);
    }
}

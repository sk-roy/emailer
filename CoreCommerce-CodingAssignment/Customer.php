<?php

class Customer
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var Rental[]
     */
    private $rentals;

    /**
     * @param string $name
     */
    public function __construct($name)
    {
        $this->name = $name;
        $this->rentals = [];
    }

    /**
     * @return string
     */
    public function name()
    {
        return $this->name;
    }

    /**
     * @param Rental $rental
     */
    public function addRental(Rental $rental)
    {
        $this->rentals[] = $rental;
    }

    /**
     * @param Rental $rental
     */
    public function getRentals()
    {
        return $this->rentals;
    }

    public function getTotalCharge()
    {
        $totalAmount = 0;
        foreach ($this->getRentals() as $rental) {
            $thisAmount = $rental->getAmount();
            $totalAmount += $thisAmount;
        }
        return $totalAmount;
    }

    public function getTotalfrequentRenterPoints()
    {
        $frequentRenterPoints = 0;
        foreach ($this->getRentals() as $rental) {
            $frequentRenterPoints += $rental->getFrequentRenterPoints();
        }
        return $frequentRenterPoints;
    }




    /**
     * @return string
     */
    public function statement($customer)
    {

        $totalAmount = 0;
        $frequentRenterPoints = 0;

        if(!($customer instanceof Customer)) 
        {
            $result .="Invalid Customer Object". PHP_EOL;
        }
        else {
            $result = 'Rental Record for ' . $customer->name() . PHP_EOL;
        
            if(!$customer->rentals || sizeof($customer->rentals)< 1) {
                $result .= $customer->name . " doesn't have any rental history". PHP_EOL;
            }

            foreach ($customer->rentals as $rental) {
                    $thisAmount = 0;
                    $thisAmount += $rental->defaultRent();
                    if ($rental->daysRented() > $rental->freeRentPeriod()) {
                            $thisAmount += ($rental->daysRented() - $rental->freeRentPeriod()) * $rental->rentPerDay();
                    }
            
                $totalAmount += $thisAmount;

                $frequentRenterPoints++;
                if ($rental->movie()->priceCode() === (int) PriceCodes::NEW_RELEASE && $rental->daysRented() > 1) {
                    $frequentRenterPoints++;
                }

                $result .= "\t" . str_pad($rental->movie()->name(), 30, ' ', STR_PAD_RIGHT) . "\t" . $thisAmount . PHP_EOL;
            }
        
        }
            $result .= 'Amount owed is ' . $totalAmount . PHP_EOL;
            $result .= 'You earned ' . $frequentRenterPoints . ' frequent renter points' . PHP_EOL;
        
        return $result;
    }

}

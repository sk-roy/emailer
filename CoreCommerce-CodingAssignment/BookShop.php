<?php 

class BookShop {

     /**
     * @var Movies[]
     */
    private $movies;
    
    /**
     * @param Movie $movie
     */
    public function addMovie(Movie $movie)
    {
        $this->movies[] = $movie;
    }

    /**
     * @param Movie $movie
     */
    public function getMovies()
    {
        return $this->movies;
    }

    /**
     * @return string
     */
    public function statement($customer)
    {
        
        if(!($customer instanceof Customer)) 
        {
            $result .="Invalid Customer Object". PHP_EOL;
        }
        else {
            $result = 'Rental Record for ' . $customer->name() . '</h1>'. PHP_EOL;
 
            if(!$customer->getRentals() || sizeof($customer->getRentals())< 1) {
                $result .= $customer->name() . " doesn't have any rental history". PHP_EOL;
            }
            else  {
              
                foreach ($customer->getRentals() as $rental) {
                    $result .= "\t" . str_pad($rental->movie()->name(), 30, ' ', STR_PAD_RIGHT) . "\t" . $rental->getAmount() . PHP_EOL;
                }
            }
            
        }

        $result .= 'Amount owed is ' . $customer->getTotalCharge() . PHP_EOL;
        $result .= 'You earned ' . $customer->getTotalfrequentRenterPoints() . ' frequent renter points' . PHP_EOL;

        return $result;
    }
    
    /**
     * @return string
     */
    public function htmlStatement($customer)
    {
        $totalAmount = 0;
        $frequentRenterPoints = 0;

        if(!($customer instanceof Customer)) 
        {
            $result .="Invalid Customer Object". PHP_EOL;
        }
        else {
            $result = '<h1>Rental Record for <em>' . $customer->name() . '</em></h1>'. PHP_EOL;
 
            if(!$customer->getRentals() || sizeof($customer->getRentals())< 1) {
                $result .= $customer->name() . " doesn't have any rental history". PHP_EOL;
            }
            else  {
                $result .= '<ul>' . PHP_EOL;

                foreach ($customer->getRentals() as $rental) {
                    $result .= "\t<li>" . str_pad($rental->movie()->name(), 30, ' ', STR_PAD_RIGHT) . "\t" . $rental->getAmount() . '</li>'. PHP_EOL;
                }
                $result .= '</ul>' . PHP_EOL;
            }
            
        }

        $result .= '<p>Amount owed is <em>' . $customer->getTotalCharge() . '</em></p>'. PHP_EOL;
        $result .= '<p>You earned ' . $customer->getTotalfrequentRenterPoints() . ' frequent renter points' . '</p>'. PHP_EOL;

        return $result;
    }
}

?>
<?php

require_once('Movie.php');
require_once('Rental.php');
require_once('Customer.php');
require_once('Constants.php');
require_once('BookShop.php');


$bookShop = new BookShop();

//Generate Stock of Movies
$bookShop->addMovie(new Movie(
        'Back to the Future',
        (int)PriceCodes::CHILDRENS
));

$bookShop->addMovie(new Movie(
    'Office Space',
    (int)PriceCodes::REGULAR
));

$bookShop->addMovie(new Movie(
    'The Big Lebowski',
    (int) PriceCodes::NEW_RELEASE
));


// Rental Movies
$rental1 = new Rental( $bookShop->getMovies()[0], 4, 1.5, 1.5, 3);
$rental2 = new Rental( $bookShop->getMovies()[1], 3, 2, 1.5, 2);
$rental3 = new Rental( $bookShop->getMovies()[2], 5, 0, 3, 0);


$customer = new Customer('Joe Schmoe');
$customer->addRental($rental1);
$customer->addRental($rental2);
$customer->addRental($rental3);

$customer2 = new Customer('Ross geller');
echo $bookShop->statement($customer);
//echo $bookShop->htmlStatement($customer);


//TODO: Generate Price Code factory to return pricecode class with DefaultPrice, PricePerDay and FreeRentedDays for Given PriceCode

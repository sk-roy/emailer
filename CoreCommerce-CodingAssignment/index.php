<?php

require_once('Movie.php');
require_once('Rental.php');
require_once('Customer.php');
require_once('Constants.php');
require_once('MovieShop.php');
require_once('BasePriceCode.php');
require_once('PriceCodeFactory.php');
require_once('IBasePriceCode.php');


$MovieShop = new MovieShop();
$MovieShop->addMovie(new Movie('Back to the Future', PriceCodes::CHILDRENS));
$MovieShop->addMovie(new Movie('Office Space', PriceCodes::REGULAR));
$MovieShop->addMovie(new Movie('The Big Lebowski', PriceCodes::NEW_RELEASE));
$MovieShop->addMovie(new Movie('Journey to the center of the Earth', PriceCodes::BLOCKBUSTER));

$rental1 = new Rental($MovieShop->getMovies()[0], 4);
$rental2 = new Rental($MovieShop->getMovies()[1], 3);
$rental3 = new Rental($MovieShop->getMovies()[2], 5);
$rental4 = new Rental($MovieShop->getMovies()[3], 3);


$customer = new Customer('Joe Schmoe');
$customer->addRental($rental1);
$customer->addRental($rental2);
$customer->addRental($rental3);
$customer->addRental($rental4);

$customer2 = new Customer('Ross Geller');

echo $MovieShop->statement($customer);
echo $MovieShop->htmlStatement($customer);
echo $MovieShop->htmlStatement($customer2);
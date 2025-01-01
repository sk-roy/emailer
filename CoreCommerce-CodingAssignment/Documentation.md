## Overview

The Movie Rental Application is a system designed to manage a movieshop’s rental services for movies. It uses object-oriented programming principles and includes an implementation of the Factory Design Pattern for flexible and scalable handling of movie pricing logic.


## IBasePriceCode Interface

# Methods
1. getDefaultRent(): Default rental cost.
2. getRentPerDay(): Additional cost per day.
3. getFreeRentDays(): Free rental days.
4. getAmount(): Computes rental cost for a given duration.
5. getFrequentRenterPoints(): Computes reward points for a given duration


## BasePriceCode Class

A base implementation of IBasePriceCode.
# Properties
1. defaultRent (float): Base rental cost.
2. rentPerDay (float): Daily rental cost.
3. freeRentDays (int): Number of free days.

# Methods
Implements IBasePriceCode methods with shared behavior.


## Specific Pricing Classes

Customized implementations of pricing logic for different price codes.
1. ChildrenPriceCode
2. RegularPriceCode
3. NewReleasePriceCode
4. BlockbusterPriceCode


## PriceCodeFactory Class

Creates price code instances based on the provided code.
# Methods
1. create(): Returns an appropriate IBasePriceCode instance.


## Movie Class

Represents a movie with its name and price code.
# Properties
1. name (string): Name of the movie.
2. priceCode (int): Pricing code for the movie.

# Methods
1. name(): Returns the movie’s name.
2. priceCode(): Returns the movie’s price code.


## Rental Class

Represents a customer’s movie rental.
# Properties
1. movie (Movie): The rented movie.
2. daysRented (int): Duration of the rental.
3. priceCodeObj (IBasePriceCode): Pricing logic for the rental.

# Methods
1. movie(): Returns the rented movie.
2. daysRented(): Returns the rental duration.
3. getAmount(): Calculates the rental’s total cost.
4. getFrequentRenterPoints(): Determines reward points earned.


## Customer Class

Represents a customer renting movies.
# Properties
1. name (string): Customer’s name.
2. rentals (array): Customer’s rental list.

# Methods
1. name(): Returns the customer’s name.
2. addRental(): Adds a rental to the customer’s list.
3. getRentals(): Retrieves the customer’s rentals.
4. getTotalCharge(): Computes the total charge for rentals.
5. getTotalFrequentRenterPoints(): Computes total frequent renter points.


## MovieShop Class

Handles movie inventory and generates customer rental statements.
# Properties
1. movies (array): Inventory of movies.

# Methods
1. addMovie(): Adds a movie to the inventory.
2. getMovies(): Retrieves the movie list.
3. statement(): Produces a plain-text rental statement.
4. htmlStatement(): Produces an HTML rental statement.


## Application Workflow

1. Create Movies: Add movies to the MovieShop inventory using addMovie().
2. Generate Rentals: Use PriceCodeFactory to create the appropriate IBasePriceCode instance and pass it to the Rental constructor.
3. Add Rentals to Customer: Use addRental() to associate rentals with a customer.
4. Generate Statements:
    a. Use statement() for plain-text statements.
    b. Use htmlStatement() for HTML-formatted statements.

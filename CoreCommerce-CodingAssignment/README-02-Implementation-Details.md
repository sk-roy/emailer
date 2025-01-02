## Overview

The Movie Rental Application is a system designed to manage a movieshop’s rental services for movies. It uses object-oriented programming principles and includes an implementation of the Factory Design Pattern for flexible and scalable handling of movie pricing logic.

## Refactoring: 
Initially we thought to build MovieShop class and move shop related works (List of Movies, Add Movies, Generate Statements) to there. Although it was not required for this task, but we thought it could be beneficial in future if we want to handle multiple shops through this. If needed we will integrate more classes for each of Shop (implementing a base interface).

We have removed PriceCodes from Movie class and created an ENUM under Constants.php. Later we tasformed it to a Class for ease. 

To support future maintainability of Price Codes, Additional Category adding, flexible calculations we introduced factory design pattern here. For each of the category/price code, there will be a class implementing an interface named IBasePriceCode consisting of defaultRent, RentPerDay, RentFreeDays and methods to calculate TotalAmount and FrequentRenterPoints. Because of this change, we will be able to add more categories/price codes easily without touching existing implementation. Also, each of the price codes will have separate and independent Pricing mechanism. 

statement() and htmlStatement() methods have been refactored to support two different type of formats. However, to do this in a better way, we had to move all calculations from this methods to Rental class. As the pricing depends on each rental (Movie, Price Code etc), we created two methods (getAmount() and getFrequentRenterPoints()) within Rental class and used them in statement(), htmlStatement() methods. This methods of Rental class will be using individual PriceCode Implementations for generating those totalAmount and Points.

As a test, we have added BlockbusterPriceCode and a movie of this category in index.php. 


# Purpose of Implementing Factory Design Pattern.

1. Centralized Object Creation: The PriceCodeFactory centralizes the creation of price code objects (ChildrenPriceCode, RegularPriceCode, NewReleasePriceCode). This eliminates the need to hardcode object creation logic in multiple places, promoting consistency and reducing code duplication.
2. Scalability: Adding a new price code (e.g., SciFiPriceCode) requires only:
    a. Defining a new class inheriting from BasePriceCode.
    b. Updating the factory to recognize the new price code.
    c. Adding a new constant property to PriceCodes class
3. Improved Maintainability: Encapsulating pricing logic within dedicated classes makes it easier to maintain and update specific rules. Any changes to pricing logic are localized within the respective PriceCode class, reducing the risk of breaking other parts of the application.
4. Maintainability: Using the PriceCodeFactory, object creation logic is centralized, reducing duplication and potential errors.
5. Customization: Specific behaviors, like extra frequent renter points for new releases, are easily implemented in the respective class.


# Overview on Updated Class Structure and functionalities

## IBasePriceCode Interface

# Methods
1. getDefaultRent(): Default rental cost.
2. getRentPerDay(): Additional cost per day.
3. getFreeRentDays(): Rent Free days.
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

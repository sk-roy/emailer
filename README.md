## Setup Dev Environment:

1. Open Terminal and Go to project folder
2. RUN `composer install`
3. Update Database Connection in .env
4. Update MAIL_* configs in .env
5. RUN `php artisan migrate`
6. RUN `php artisan test`
8. RUN `php artisan queue:work`


## Generate API Documentation

1. RUN `php artisan scribe:generate`


## Run the Application:

RUN `php artisan serve` and test APIs. API documentations can be found in ....


## Application Details:

In this project, we have built an application with laravel 11 and PHP 8.3. There are two REST APIs built with following Details. In these APIs, we have added validation and wrote some logs which can be restructured based on Project. We tried to implement generic Response Model to send response of all APIs in same structure.  


### API 01: SendEmail: 

This API (POST api/emails) will take JSON request with email, subject, message, attachment_filename and attachment (BASE64). This request will be added to Database table named emails with status 'in-queue' and also added to Laravel Job Queue. Separate Queue processor will be running and continously process these JOBs from Queue. After processing the Queue, it will update the status of the email in DB table with success or failed status. 

While sending email, we have used mime_content_type() method to get the content type of the file from filename. 



### API 02: Get Email list with Status:

This API (GET api/emails) will take per_page and page as JSON request and will send emails from DB table according to the request with Status column.



## Tests: 

Under tests/Unit/EmailApiTest.php, Four (04) tests have been written for each of these 02 APIs. We haven't covered all test cases intentionally to save time. 


## Breakdown & Estimation [13.25 hours]

1. Setup Laravel Application and SMTP for mail [1 hour]
2. Migrations [0.25 hours]
3. Email Model and Controller [4 hours]
4. Email Sending Asynchronously (Job Class, Mailable class, view template) [4 hours]
5. Unit Tests (Two unit tests for each endpoints) [2 hours]
6. API Documentation [2 hours]

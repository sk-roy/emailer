## Setup Dev Environment:

1. Open Terminal and Go to project folder
2. RUN `composer install`
3. Update Database Connection in .env
4. Update MAIL_* configs in .env
5. RUN `php artisan migrate`
6. RUN `php artisan test`
8. RUN `php artisan queue:work`
9. RUN `php artisan scribe:generate`


## Run the Application:

RUN `php artisan serve` and test APIs. API documentations can be found in /docs/index.html


## Application Details:

In this project, we have built an application with laravel 11 and PHP 8.3. There are two REST APIs built with following Details. In these APIs, we have added validation and wrote some logs which can be restructured based on Project. We tried to implement generic Response Model to send response of all APIs in same structure.  


### API 01: SendEmail: 

This API (POST api/emails) will take JSON request with email, subject, message, attachment_filename and attachment (BASE64). This request will be added to Database table named emails with status 'in-queue' and also added to Laravel Job Queue. Separate Queue processor will be running and continously process these JOBs from Queue. After processing the Queue, it will update the status of the email in DB table with success or failed status. 

While sending email, we have used \GuzzleHttp\Psr7\MimeType::fromFilename() method to get the content type of the file from filename. 



### API 02: Get Email list with Status:

This API (GET api/emails) will take per_page and page as JSON request and will send emails from DB table according to the request with Status column.



## Tests: 

Under tests/Unit/EmailApiTest.php, Four (04) tests have been written for each of these 02 APIs. There are scopes to write more tests against these to increase code coverage.


## Spent Time: 9.75 hrs
1. Setup Laravel Application and SMTP for E-mail Configuration - 0.5 hour
2. Design DB Schema and write migrations - 0.25 hours
3. Write structures of APIs with Models, Controllers - 2 hours
4. Get mime type from Base64 code - 2 hours
4. Implement Sending Email Asynchronously (Job , Mailable class, view template, Update status after successful email sent) - 2 hours
5. Integrate and Write Unit Tests (Two unit tests for each endpoints) - 2 hours
6. Integrate Scribe and prepare APIs for correct documentation generation - 0.5 hrs
7. Write Documentations: 0.5 hr

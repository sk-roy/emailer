<?php

namespace Tests\Unit;

use App\Models\Email;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;


class EmailApiTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test email submission API
     */
    public function test_email_submission_api()
    {
        $payload = [
            'email' => 'test@example.com',
            'subject' => 'Test Subject',
            'message' => 'This is a test message.',
            'attachment_filename' => 'picture.jpg',
            'attachment' => 'iVBORw0KGgoAAAANSUhEUgAAABgAAAAYCAYAAADgdz34AAAABHNCSVQICAgIfAhkiAAAAAlwSFlzAAAApgAAAKYB3X3/OAAAABl0RVh0U29mdHdhcmUAd3d3Lmlua3NjYXBlLm9yZ5vuPBoAAANCSURBVEiJtZZPbBtFFMZ/M7ubXdtdb1xSFyeilBapySVU8h8OoFaooFSqiihIVIpQBKci6KEg9Q6H9kovIHoCIVQJJCKE1ENFjnAgcaSGC6rEnxBwA04Tx43t2FnvDAfjkNibxgHxnWb2e/u992bee7tCa00YFsffekFY+nUzFtjW0LrvjRXrCDIAaPLlW0nHL0SsZtVoaF98mLrx3pdhOqLtYPHChahZcYYO7KvPFxvRl5XPp1sN3adWiD1ZAqD6XYK1b/dvE5IWryTt2udLFedwc1+9kLp+vbbpoDh+6TklxBeAi9TL0taeWpdmZzQDry0AcO+jQ12RyohqqoYoo8RDwJrU+qXkjWtfi8Xxt58BdQuwQs9qC/afLwCw8tnQbqYAPsgxE1S6F3EAIXux2oQFKm0ihMsOF71dHYx+f3NND68ghCu1YIoePPQN1pGRABkJ6Bus96CutRZMydTl+TvuiRW1m3n0eDl0vRPcEysqdXn+jsQPsrHMquGeXEaY4Yk4wxWcY5V/9scqOMOVUFthatyTy8QyqwZ+kDURKoMWxNKr2EeqVKcTNOajqKoBgOE28U4tdQl5p5bwCw7BWquaZSzAPlwjlithJtp3pTImSqQRrb2Z8PHGigD4RZuNX6JYj6wj7O4TFLbCO/Mn/m8R+h6rYSUb3ekokRY6f/YukArN979jcW+V/S8g0eT/N3VN3kTqWbQ428m9/8k0P/1aIhF36PccEl6EhOcAUCrXKZXXWS3XKd2vc/TRBG9O5ELC17MmWubD2nKhUKZa26Ba2+D3P+4/MNCFwg59oWVeYhkzgN/JDR8deKBoD7Y+ljEjGZ0sosXVTvbc6RHirr2reNy1OXd6pJsQ+gqjk8VWFYmHrwBzW/n+uMPFiRwHB2I7ih8ciHFxIkd/3Omk5tCDV1t+2nNu5sxxpDFNx+huNhVT3/zMDz8usXC3ddaHBj1GHj/As08fwTS7Kt1HBTmyN29vdwAw+/wbwLVOJ3uAD1wi/dUH7Qei66PfyuRj4Ik9is+hglfbkbfR3cnZm7chlUWLdwmprtCohX4HUtlOcQjLYCu+fzGJH2QRKvP3UNz8bWk1qMxjGTOMThZ3kvgLI5AzFfo379UAAAAASUVORK5CYII='

        ];

        $response = $this->post('/api/emails', $payload);

        $response->assertStatus(200);
        $this->assertEquals(true, $response->json('success'));
    }

    /**
     * Test email submission validation errors
     */
    public function test_email_submission_validation()
    {
        $payload = [
            'email' => 'invalid-email', // Invalid email format
            'message' => '', // Empty message
        ];

        $response = $this->post('/api/emails', $payload);

        $this->assertEquals(false, $response->json('success'));
        $this->assertEquals(422, $response->json('error_code'));
        $this->assertDatabaseMissing('mail-logs', ['email' => $payload['email']]);
    }

    /**
     * Test email list API with pagination
     */
    public function test_email_list_api_with_pagination_page_size()
    {
        // Create dummy email records
        Email::factory()->count(35)->create();

        $response = $this->get('/api/emails?per_page=20');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                    'data' => [                    
                        'current_page',
                        'data' => [
                            '*' => [
                                'subject',
                                'email',
                                'message',
                                'attachment_filename',
                                'status',
                            ],
                        ],
                        'first_page_url',
                        'last_page_url',
                        'next_page_url',
                        'prev_page_url',
                        'total',
                    ],
                    'message',
                    'success',
                    'error_code',
                 ]);

        $this->assertEquals(200, $response->json('error_code'));
        $this->assertCount(20, $response->json('data.data'));
    }

    /**
     * Test email list API with default page size 
     */
    public function test_email_list_api_with_pagination_default_page_size_last_page_count()
    {
        // Create dummy email records
        Email::factory()->count(35)->create();

        $response = $this->get('/api/emails?page=4');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                    'data' => [                    
                        'current_page',
                        'data' => [
                            '*' => [
                                'subject',
                                'email',
                                'message',
                                'attachment_filename',
                                'status',
                            ],
                        ],
                        'first_page_url',
                        'last_page_url',
                        'next_page_url',
                        'prev_page_url',
                        'total',
                    ],
                    'message',
                    'success',
                    'error_code',
                 ]);

        // Ensure only 10 results are returned in the first page
        $this->assertCount(5, $response->json('data.data'));
    }
   
}

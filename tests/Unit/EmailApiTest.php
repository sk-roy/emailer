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

        $response = $this->get('/api/emails?per_page=20&page=2');

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
        $this->assertCount(15, $response->json('data.data'));
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

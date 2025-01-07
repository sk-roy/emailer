<?php

namespace App\Http\Controllers;

use App\Jobs\SendEmailJob;
use App\Enums\StatusEnum;
use App\Models\Email;
use App\Models\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;


/**
 *  
 * APIs for managing emails, including sending and retrieving email records.
 * 
 */
class EmailController extends Controller
{
    /**
     * Send Email
     * 
     * Add an email to the queue for sending.
     * 
     * @endpoint POST /emails
     * 
     * @bodyParam subject string required The subject of the email. Example: "Meeting Reminder"
     * @bodyParam email string required The recipient's email address. Example: "user@example.com"
     * @bodyParam message string required The body of the email. Example: "Don't forget our meeting tomorrow at 10 AM."
     * @bodyParam attachment string optional A base64 encoded string of the email attachment. Example: "dGVzdCBhdHRhY2htZW50IGNvbnRlbnQ="
     * @bodyParam attachment_filename string required_with:attachment The filename of the attachment. Example: "file.pdf"
     *
     */
    public function sendEmail(Request $request)
    {
        Log::debug('Add email to the queue.', ['request' => $request->all()]);
        $response = new ApiResponse();

        try {
            $request->validate([
                'subject' => 'required|string',
                'email' => 'required|email',
                'message' => 'required|string',
                'attachment' => 'nullable|string',
                'attachment_filename' => 'required_with:attachment|nullable|string',
            ]);

            $data = $request->only(['subject', 'email', 'message', 'attachment', 'attachment_filename']);
            $mail = Email::create([
                'subject' => $data['subject'],
                'email' => $data['email'],
                'message' => $data['message'],
                'attachment' => $data['attachment'] ?? null,
                'attachment_filename' => $data['attachment_filename'] ?? null,
                'status' => StatusEnum::STATUS_IN_QUEUE,
            ]);

            SendEmailJob::dispatch($mail, $data);

            $response->setMessage('Email queued successfully.');
            $response->setSuccess(true);
            $response->setErrorCode(200);
        } catch (ValidationException $e) {
            Log::error('Validation Error:', ['errors' => $e->errors()]);

            $response->setMessage($e->getMessage());
            $response->setSuccess(false);
            $response->setErrorCode(422);
        } catch (\Exception $e) {
            $response->setMessage('An error occurred while processing your request.');
            $response->setSuccess(false);
            $response->setErrorCode(500);

            Log::error($response->getMessage(), [
                'error' => $e->getMessage(),
                'request' => $request->all(),
            ]);
        }

        Log::debug($response->getMessage(), ['response' => $response]);
        return response()->json($response);
    }

    /**
     * Get Email list with Status
     * 
     * Retrieve a paginated list of emails.
     * 
     * @queryParam per_page int optional Number of emails to retrieve per page. Defaults to 10. Example: 15
     * @queryParam page int optional Number of emails to retrieve the specific page. Defaults to 1.
     * 
     * @apiResourceModel App\Models\Email
     */
    public function getEmailList(Request $request)
    {
        Log::debug('Retrieve emails.', ['request' => $request->all()]);
        $response = new ApiResponse();

        try {
            $perPage = $request->get('per_page', 10);
            $search  = $request->get('search', '');
            $orderBy  = $request->get('order_by', 'updated_at');
            $orderDirection  = $request->get('order_direction', 'desc');

            $emails = Email::when($request->search, function ($query, $search) {
                                $query->where('message', 'like', "%$search%")
                                    ->orWhere('subject', 'like', "%$search%")
                                    ->orWhere('email', 'like', "%$search%")
                                    ->orWhere('attachment_filename', 'like', "%$search%");
                            }) 
                            ->orderBy($orderBy, $orderDirection)
                            ->paginate($perPage)
                            ->toArray();

            $emails['timezone'] = config('app.timezone');

            $response->setData($emails);
            $response->setMessage('Email list retrieved successfully.');
            $response->setSuccess(true);
            $response->setErrorCode(200);

            Log::debug($response->getMessage());
        } catch (\Exception $e) {
            $response->setMessage('Failed to retrieve email list.');
            $response->setSuccess(false);
            $response->setErrorCode(500);

            Log::error($response->getMessage(), [
                'error' => $e->getMessage(),
                'request' => $request->all(),
            ]);
        }

        Log::debug($response->getMessage(), ['response' => $response]);
        return response()->json($response);
    }
}

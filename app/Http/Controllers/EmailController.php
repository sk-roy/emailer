<?php

namespace App\Http\Controllers;

use App\Jobs\SendEmailJob;
use App\Models\Email;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Mail;
use App\Mail\TestMail;

class EmailController extends Controller
{
    public function sendEmail(Request $request)
    {
        Log::info('Add email to the queue.', ['request' => request()->all()]);
        $response = [
            'data' => [],
            'message' => '',
            'success' => false,
            'status_code' => 500,
        ];

        try {
            $request->validate([
                'subject' => 'string',
                'email' => 'required|email',
                'message' => 'required|string',
                'attachment' => 'nullable|string',
                'attachment_filename' => 'required_with:attachment|string',
            ]);

            $data = $request->only(['subject', 'email', 'message', 'attachment', 'attachment_filename']);
            $mail = Email::create([
                'subject' => $data['subject'] ?? 'Untitled!!!',
                'email' => $data['email'],
                'message' => $data['message'],
                'attachment_filename' => $data['attachment_filename'] ?? null,
                'status' => 'in-queue',
            ]);
            
            if ($request->hasFile('attachment')) {
                $fileData = base64_decode($data['attachment']);
                Storage::disk('public')->put($data['attachment_filename'], $fileData);
                $data['attachment_path'] = $request->file('attachment')->store('attachments');
            }

            SendEmailJob::dispatch($mail, $data);

            $response['success'] = true;
            $response['message'] = 'Email queued successfully';
            $response['status_code'] = 200;
            
        } catch (ValidationException $e) {
            Log::error('Validation Error:', ['errors' => $e->errors()]);        
            $response['success'] = false;
            $response['message'] = $e->getMessage();
            $response['status_code'] = 422;
         } catch (\Exception $e) {
            $response['success'] = false;
            $response['message'] = 'An error occurred while processing your request';
            $response['status_code'] = 500;

            Log::error($response['message'], [
                'error' => $e->getMessage(),
                'request' => $request->all(),
            ]);
        }

        Log::info($response['message'], ['response' => $response]);
        return response()->json($response);
    }

    public function getEmailList(Request $request)
    {
        Log::info('Retrive emails.', ['request' => request()->all()]);
        $response = [
            'data' => [],
            'message' => '',
            'success' => false,
            'status_code' => 500,
        ];

        try {
            $perPage = $request->get('per_page', 10);

            $emails = Email::select([
                'subject',
                'email',
                'message',
                'attachment_filename',
                'status'
            ])->paginate($perPage);
            
            $response['data'] = $emails;
            $response['success'] = true;
            $response['message'] = 'Email list retrieved successfully.';
            $response['status_code'] = 200;

            Log::info($response['message'], ['per_page' => $perPage]);

        } catch (\Exception $e) {            
            $response['data'] = [];
            $response['success'] = false;
            $response['message'] = 'Failed to retrieve email list.';
            $response['status_code'] = 500;

            Log::error($response['message'], [
                'error' => $e->getMessage(),
                'request' => $request->all(),
            ]);
        }

        Log::info($response['message'], ['response' => $response]);
        return response()->json($response);
    }


}

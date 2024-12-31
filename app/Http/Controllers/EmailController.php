<?php

namespace App\Http\Controllers;

use App\Jobs\SendEmailJob;
use App\Models\Email;
use App\Models\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Mail;

class EmailController extends Controller
{
    public function sendEmail(Request $request)
    {
        Log::info('Add email to the queue.', ['request' => request()->all()]);
        $response = new ApiResponse();

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


            $response->setMessage('Email queued successfully.');
            $response->setSuccess(true);
            $response->setErrorCode(200);
            
        } catch (ValidationException $e) {
            Log::error('Validation Error:', ['errors' => $e->errors()]); 

            $response->setMessage($e->getMessage());
            $response->setSuccess(false);
            $response->setErrorCode(422);
         } catch (\Exception $e) {
            
            $response->setMessage('An error occurred while processing your request');
            $response->setSuccess(false);
            $response->setErrorCode(500);

            Log::error($response->getMessage(), [
                'error' => $e->getMessage(),
                'request' => $request->all(),
            ]);
        }

        Log::info($response->getMessage(), ['response' => $response]);
        return response()->json($response);
    }

    public function getEmailList(Request $request)
    {
        Log::info('Retrive emails.', ['request' => request()->all()]);
        $response = new ApiResponse();

        try {
            $perPage = $request->get('per_page', 10);

            $emails = [];
            $emails = Email::select([
                'subject',
                'email',
                'message',
                'attachment_filename',
                'status'
            ])->paginate($perPage);
            $emailsArray = $emails->toArray();
            
            $response->setData($emailsArray);
            $response->setMessage('Email list retrieved successfully.');
            $response->setSuccess(true);
            $response->setErrorCode(200);

            Log::info($response->getMessage(), ['per_page' => $perPage]);

        } catch (\Exception $e) {  
            $response->setMessage('Failed to retrieve email list.');
            $response->setSuccess(false);
            $response->setErrorCode(500);

            Log::error($response->getMessage(), [
                'error' => $e->getMessage(),
                'request' => $request->all(),
            ]);
        }

        Log::info($response->getMessage(), ['response' => $response]);
        return response()->json($response);
    }


}

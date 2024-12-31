<?php

namespace App\Jobs;

use App\Mail\TestMail;
use App\Models\Email;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class SendEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $mail;
    protected $data;

    /**
     * Create a new job instance.
     */
    public function __construct(Email $mail, array $data)
    {
        $this->mail = $mail;
        $this->data = $data;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        try {            
            Mail::to($this->data['email'])->send(new TestMail($this->data));
            $this->mail->update(['status' => 'sent']);
            Log::info('Email job processed successfully', ['Email' => $this->data['email']]);
            
        } catch (\Exception $e) {
            $this->mail->update(['status' => 'failed']);
            Log::error('Error processing email job', [
                'error' => $e->getMessage(),
                'email' => $this->data['email'],
            ]);
        }
    }
}

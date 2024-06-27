<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Mail\VerifyEmail as MailVerifyEmail;
use Illuminate\Support\Facades\Mail;

class SendEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $to;
    protected $subject;
    protected $body;
    protected $type;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($to, $subject, $body, $type)
    {
        $this->to = $to;
        $this->subject = $subject;
        $this->body = $body;
        $this->type = $type;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if ($this->type === 'user_email_verify') {
            Mail::to($this->to)->send(new MailVerifyEmail($this->body['name'], $this->body['url'], $this->body['verify']));
        }
    }
}

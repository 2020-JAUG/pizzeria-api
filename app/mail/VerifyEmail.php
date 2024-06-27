<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class VerifyEmail extends Mailable
{
    use Queueable, SerializesModels;

    protected $name;
    protected $url;
    protected $verify;


    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($name, $url, $verify)
    {
        $this->name = $name;
        $this->url = $url;
        $this->verify = $verify;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('mail.userVerifyEmail', ['name' => $this->name, 'url' => $this->url, 'verify' => $this->verify])
            ->subject('Email de verificación de la cuenta')
            ->from(env('MAIL_FROM_ADDRESS'));
    }
}

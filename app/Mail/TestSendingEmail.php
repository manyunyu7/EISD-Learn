<?php

namespace App\Mail;

use Aws\Crypto\MetadataEnvelope;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Queue\SerializesModels;

class TestSendingEmail extends Mailable
{
    use Queueable, SerializesModels;
    public $email;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($email)
    {
        $this->email = $email;
    }
    
    /**s
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        // return $this->view('emails.test-mail')->with('email', $this->email);

        return $this->view('emails.test-mail')->with([
            'email'=> $this->email,
            'resetLink' => route('password.reset', ['token' => 'example-token',
            'email' => $this->email])
        ]);
    }
}

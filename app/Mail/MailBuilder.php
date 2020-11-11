<?php

namespace App\Mail;

// use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MailBuilder extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The order instance.
     *
     * @var Order
     */
    public $user;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        // return $this->view('emails.reset_password')->with(['user' => $this->user]);
        return "Hello World";
    }
}

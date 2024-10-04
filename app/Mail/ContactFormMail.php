<?php
// app/Mail/ContactFormMail.php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContactFormMail extends Mailable
{
    use Queueable, SerializesModels;

    public $name;
    public $email;
    public $comment;

    public function __construct($name, $email, $comment)
    {
        $this->name = $name;
        $this->email = $email;
        $this->comment = $comment;
    }

    public function build()
    {
        return $this->view('emails.contact-form')
            ->with([
                'name' => $this->name,
                'email' => $this->email,
                'comment' => $this->comment,
            ]);
    }
}

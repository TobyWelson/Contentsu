<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MailAction extends Mailable
{
    use Queueable, SerializesModels;

    protected $title;
    protected $text;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($subject, $text)
    {
        $this->title = $subject;
        $this->text = $text;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->text('emails.flame')
        ->subject($this->title)
        ->with([
            'text' => $this->text,
          ]);

    }
}

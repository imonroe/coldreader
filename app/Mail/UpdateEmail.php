<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class UpdateEmail extends Mailable
{
    use Queueable, SerializesModels;
	
	public $update_message;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($message)
    {
        $this->update_message = $message;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        //return $this->view('view.name');

		return $this->from(['address' => 'coldreader@ianmonroe.com', 'name' => 'Coldreader'])
                	->view('emails.update_email');

    }
}

<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Property;

class ContactOwnerMail extends Mailable
{
    use Queueable, SerializesModels;
    public $property;
    public $data;

    public function __construct(Property $property, $data)
    {
        $this->property = $property;
        $this->data = $data;
    }

    public function build()
    {
        return $this->subject('Un client vous contacte - Empire-Immo')
            ->view('emails.contact_owner')
            ->with(['property'=>$this->property,'data'=>$this->data]);
    }
}

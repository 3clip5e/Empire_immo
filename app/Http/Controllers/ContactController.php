<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Property;
use App\Mail\ContactOwnerMail;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function contactOwner(Request $r, Property $property){
        $data = $r->validate([
            'name'=>'required|string',
            'email'=>'required|email',
            'message'=>'required|string'
        ]);

        Mail::to($property->owner->email)->send(new ContactOwnerMail($property, $data));

        return back()->with('success','Message envoyé au propriétaire');
    }
}

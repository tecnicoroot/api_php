<?php
 
namespace App\Http\Controllers;
 
use Illuminate\Support\Facades\Mail;
 
class EmailController extends Controller
{
      
    public function send() {
        $text = "Welcome to our website API\n";
            $text .= "This is your credentials\n";
            $text .= "Your Client ID : 1'{}'\n";
            $text .= "Your Client SECRET : 2'{}'\n";
            $text .= "Grant Type : 'credential'\n";
            $text .= "You are now able to use our services!'\n";
        $data = [];
        Mail::raw($text, function($message) {
        $message->to('easyselva@gmail.com', 'Arunkumar')->subject('Test Mail from Selva');
        $message->from('selva@snamservices.com','Selvakumar');
        });
        echo "Email Sent. Check your inbox.";
         
    }
}
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Mail; // Corrected from 'Use Mail;'

class MailController extends Controller
{
    //

    public function sendMail(){

echo "hello";
$data=array('name'=>"aysha");
Mail::send('mail',$data,function($message){
$message->to('aysha092@gmail.com');
$message->subject('laravel mail');
$message->from('bholataysha52@gmail.com');
});

    }

}

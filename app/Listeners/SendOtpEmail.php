<?php

namespace App\Listeners;

use App\Events\OtpGenerated;
use App\Mail\OtpMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendOtpEmail implements ShouldQueue
{
    public function handle(OtpGenerated $event)
    {
       // Mail::to($event->otp->email)->send(new OtpMail($event->otp));
        Mail::to('akazad914@gmail.com')->send(new OtpMail(15451));
    }
}

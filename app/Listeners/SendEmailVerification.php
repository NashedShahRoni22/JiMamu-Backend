<?php

namespace App\Listeners;

use App\Events\UserRegisteredEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;
use App\Mail\EmailVerificationMail;

class SendEmailVerification
{
    use InteractsWithQueue;

    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }


    public function handle(UserRegisteredEvent $event)
    {
        // Send verification email
        Mail::to($event->user->email)->send(new EmailVerificationMail($event->user));
    }
}

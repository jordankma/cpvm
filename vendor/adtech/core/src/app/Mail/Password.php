<?php

namespace Adtech\Core\App\Mail;

use Adtech\Application\Cms\Mail\AdtechMailable;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class Password extends AdtechMailable
{
    public function __construct()
    {
        //
    }

}
<?php 

namespace App;

use Illuminate\Notifications\Notifiable as NotifiableTrait;

class CustomNotifiable
{
    use NotifiableTrait;

    public function routeNotificationForMail($notification)
    {
        return $this->email;
    }
}
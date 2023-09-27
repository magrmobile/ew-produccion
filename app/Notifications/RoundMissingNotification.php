<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

use App\Machine;

class RoundMissingNotification extends Notification
{
    use Queueable;

    //protected $machine;
    //protected $hour;

    protected $rounds_pending;
    protected $date;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    // public function __construct(Machine $machine, $date, $hour)
    public function __construct($rounds_pending, $date)
    {
        /*$this->machine = $machine;
        $this->hour = $hour;*/
        $this->rounds_pending = $rounds_pending;
        $this->date = $date;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $url = url('/rounds/create');

        return (new MailMessage)
                    ->subject('Falta información de Rondas')
                    ->greeting('Hola ' . $notifiable->name . ',')
                    ->line('Falta un total de ' . $this->rounds_pending . 
                        ' para la fecha ' . $this->date)
                    ->action('Ingresar Rondas', $url)
                    ->line('Por favor, asegúrate de ingresar la información lo antes posible.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}

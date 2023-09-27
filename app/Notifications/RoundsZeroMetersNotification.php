<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class RoundsZeroMetersNotification extends Notification
{
    use Queueable;

    protected $machine;
    protected $shift;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($machine, $shift)
    {
        $this->machine = $machine;
        $this->shift = $shift;
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
        return (new MailMessage)
                    ->subject('Notificación de Rondas con Metros Produciros Igual a Cero')
                    ->line("Se han ingresado más de 2 rondas con metros producidos igual a cero para la máquina '{$this->machine->machine_name}' y el turno '{$this->shift}'.")
                    ->line('Por favor, verifique y tome las acciones necesarias.')
                    ->action('Ir al panel', url('/dashboard'));
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

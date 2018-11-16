<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\{
    User,
    Ccir
};

class CcirValidated extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    protected $ccir;
    protected $requester;
    protected $status;
    protected $qm;
    public function __construct(Ccir $ccir,User $requester, $status, User $qm)
    {
        $this->ccir = $ccir;
        $this->requester = $requester;
        $this->status = $status;
        $this->qm = $qm;
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
                    ->greeting('Good day!')
                    ->line('CCIR requested by '.$this->requester->name . ' was marked '.$this->status. ' by ' .$this->qm->name.'. Please await contact from QM for details. ')
                    ->action('Please visit E-Forms Portal', url('/ccir-view/'.$this->ccir->id))
                    ->line('Thank you for using our application!');
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

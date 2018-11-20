<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\{
    Ncn,
    User
};

class NcnForYourImmediateAction extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    protected $ncn;
    protected $requester;
    protected $notified;
    protected $approver;
    public function __construct(Ncn $ncn, User $requester, User $approver, User $notified)
    {
        $this->ncn = $ncn;
        $this->requester = $requester;
        $this->approver = $approver;
        $this->notified = $notified;

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
                    ->line('NCN requested by  '.$this->requester->name.' has been approved by '.$this->approver->name .'. Please validate the NCN and input the immediate action.')
                    ->action('Please visit E-Forms Portal', url('/notified-page'))
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

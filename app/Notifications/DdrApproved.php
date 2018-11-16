<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\{
    Ddr,
    User
};

class DdrApproved extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    protected $ddr;
    protected $requester;
    protected $approver;
    public function __construct(Ddr $ddr, User $requester, User $approver)
    {
        $this->ddr = $ddr;
        $this->requester = $requester;
        $this->approver = $approver;
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
                    ->line($this->requester->name.' has filed a DRDR at QMD E-forms portal. It has been approved by  '.$this->approver->name.'. Please proceed to e-forms portal to verify the request. ')
                    ->action('Please visit E-Forms Portal', url('/admin/ddr-details/'.$this->ddr->id))
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

<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\{
    User,
    Drdr
};

class ReviewerReviewedDrdr extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    protected $requester;
    protected $reviewer;
    protected $drdr;

    public function __construct(Drdr $drdr, User $requester, User $reviewer)
    {
        $this->drdr = $drdr;
        $this->requester = $requester;
        $this->reviewer = $reviewer;
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
                    ->line('Ddr filed by '.$this->requester->name.' in E-FORMS portal has been reviewed by '.$this->reviewer->name )
                    ->action('Notification Action', url('/drdr-approve/'.$this->drdr->id))
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

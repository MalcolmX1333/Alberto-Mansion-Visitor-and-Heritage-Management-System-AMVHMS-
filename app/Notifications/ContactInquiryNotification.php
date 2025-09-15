<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class ContactInquiryNotification extends Notification
{
    use Queueable;

    public $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('New Contact Inquiry')
            ->greeting('Hello,')
            ->line('You have received a new inquiry from the website contact form.')
            ->line('**Name:** ' . $this->data['name'])
            ->line('**Email:** ' . $this->data['email'])
            ->line('**Message:**')
            ->line($this->data['message'])
            ->salutation('Regards, Alberto Mansion Website');
    }
}

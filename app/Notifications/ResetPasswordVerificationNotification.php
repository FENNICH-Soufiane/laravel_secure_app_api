<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

use Ichtrojan\Otp\Otp;

class ResetPasswordVerificationNotification extends Notification
{
    use Queueable;


    public $message;
    public $subject;
    public $fromEmail;
    public $mailer;
    public $otp;


    /**
     * Create a new notification instance.
     */
    public function __construct()
    {
        //
        $this->message = "Use the below code for resetting your password";
        $this->subject = "Password resetting";
        // $this->fromEmail = "fennich0011soufiane@gmail.com";
        // $this->mailer= "smtp"; //smtp fait reference a config/mail.php mailers smtp
        $this->otp = new Otp;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $otp = $this->otp->generate($notifiable->email, "numeric", 6, 60);
        return (new MailMessage)
            // ->line('The introduction to the notification.')
            // ->action('Notification Action', url('/'))
            // ->line('Thank you for using our application!');
            
            // ->mailer('smtp')
            ->subject($this->subject)
            ->greeting('Hello ' . $notifiable->first_name)
            ->line($this->message)
            ->line("code : " . $otp->token);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}

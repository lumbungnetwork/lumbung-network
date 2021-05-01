<?php

namespace App\Notifications;

use NotificationChannels\Telegram\TelegramChannel;
use NotificationChannels\Telegram\TelegramMessage;
use Illuminate\Notifications\Notification;

class eIDRNotification extends Notification
{
    private $notification;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($notification)
    {
        $this->notification = $notification;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return [TelegramChannel::class];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toTelegram($notifiable)
    {
        $hashURL = 'https://tronscan.org/#/transaction/' . $this->notification['hash'];
        return TelegramMessage::create()
            // Optional recipient user id.
            ->to($notifiable->chat_id)
            // Markdown supported.
            ->content("*eIDR berhasil diterima!*
            \nAkun: " . $notifiable->username .
                "\nJumlah: Rp" . number_format($this->notification['amount']) .
                "\nSumber: " . $this->notification['type'] .
                "\nHash: " . $this->notification['hash'])

            // (Optional) Blade template for the content.
            // ->view('notification', ['url' => $url])

            // (Optional) Inline Buttons
            ->button('Cek Hash di Tronscan', $hashURL);
        // ->button('Download Invoice', $url);
    }
}

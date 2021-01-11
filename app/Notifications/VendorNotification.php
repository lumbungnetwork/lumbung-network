<?php

namespace App\Notifications;

use NotificationChannels\Telegram\TelegramChannel;
use NotificationChannels\Telegram\TelegramMessage;
use Illuminate\Notifications\Notification;

class VendorNotification extends Notification
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
        return TelegramMessage::create()
            // Optional recipient user id.
            ->to($notifiable->chat_id)
            // Markdown supported.
            ->content("Ada pembeli di Vendor Anda!\nSilakan periksa dan konfirmasi pesanan berikut:
            \nPembeli: " . $this->notification['buyer'] .
                "\nProduk: " . $this->notification['product'] .
                "\nTotal Belanja: " . $this->notification['price'] .
                "\nMetode Pembayaran: " . $this->notification['payment']);

        // (Optional) Blade template for the content.
        // ->view('notification', ['url' => $url])

        // (Optional) Inline Buttons
        // ->button('View Invoice', $url)
        // ->button('Download Invoice', $url);
    }
}

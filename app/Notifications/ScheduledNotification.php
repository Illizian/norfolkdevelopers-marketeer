<?php

namespace App\Notifications;

use App\Models\Event;
use App\Models\ScheduledNotification as ScheduledNotificationModel;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\Discord\DiscordChannel;
use NotificationChannels\Discord\DiscordMessage;
use NotificationChannels\Twitter\TwitterStatusUpdate;

class ScheduledNotification extends Notification
{
    use Queueable;

    /**
     * Get the notification's delivery channels.
     *
     * @param  \App\Models\ScheduledNotification  $notifiable
     * @return array
     */
    public function via(ScheduledNotificationModel $notifiable) : array
    {
        return [
            $notifiable->type,
        ];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  \App\Models\ScheduledNotification  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail(ScheduledNotificationModel $notifiable) : \Illuminate\Notifications\Messages\MailMessage
    {
        return (new MailMessage)
            ->greeting($notifiable->event->title)
            ->line($notifiable->hydrated_message)
            ->action('Read more & RSVP', $notifiable->event->rsvp_link);
    }

    /**
     * Get the Discord representation of the notification.
     *
     * @param  \App\Models\ScheduledNotification  $notifiable
     * @return \NotificationChannels\Discord\DiscordMessage
     */
    public function toDiscord($notifiable) : \NotificationChannels\Discord\DiscordMessage
    {
        return (new DiscordMessage)
            ->body($notifiable->hydrated_message);
    }

    /**
     * Get the Twitter representation of the notification.
     *
     * @param  \App\Models\ScheduledNotification  $notifiable
     * @return \NotificationChannels\Twitter\TwitterStatusUpdate
     */
    public function toTwitter($notifiable) : \NotificationChannels\Twitter\TwitterStatusUpdate
    {
        if ($notifiable->destination) {
            return new TwitterDirectMessage($notifiable->destination, $notifiable->hydrated_message);
        }

        return new TwitterStatusUpdate($notifiable->hydrated_message);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  \App\Models\ScheduledNotification  $notifiable
     * @return array
     */
    public function toArray($notifiable) : array
    {
        return [
            "destination" => $notifiable->destination,
            "message" => $notifiable->message,
        ];
    }
}
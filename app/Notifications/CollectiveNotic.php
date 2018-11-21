<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\MessageNotic;

class CollectiveNotic extends Notification
{
    use Queueable;

    protected $messageNotic;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(MessageNotic $messageNotic)
    {
        $this->messageNotic = $messageNotic;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * @param $notifiable
     * @return array
     */
    public function toDatabase($notifiable)
    {
        $content = mb_substr(str_replace('&nbsp;', '', strip_tags($this->messageNotic->content)),0,10,'utf-8');
        return [
            'id' => $this->messageNotic->id,
            'title' => $this->messageNotic->title,
            'school_name' => $this->messageNotic->school->name,
            'refuse_name' => $this->messageNotic->adminUser->name,
            'created' => optional($this->messageNotic->created_at)->toDateTimeString(),
            'content' => mb_strlen($content,'UTF8')<=10?$content:$content.'....',
        ];
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

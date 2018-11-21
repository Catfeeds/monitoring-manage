<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\MessageNotic;

class SchoolNotice extends Notification
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
        //使用数据库频道来发送邮件
        return ['database'];
    }


    public function toDatabase($notifiable)
    {
        return [
            'id' => $this->messageNotic->id,
            'title' => '学校通知,请及时查看！',
            'school_name' => $this->messageNotic->school->name,
            'refuse_name' => $this->messageNotic->adminUser->username,
            'created' => optional($this->messageNotic->created_at)->toDateTimeString(),
            'content' => $this->messageNotic->title
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

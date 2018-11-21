<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Homework;

class HomeworkNotice extends Notification
{
    use Queueable;

    protected $homework;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Homework $homework)
    {
        $this->homework = $homework;
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
       $content = mb_substr(str_replace('&nbsp;', '', strip_tags($this->homework->content)),0,10,'utf-8');
       return [
            'id' => $this->homework->id,
            'title' => $this->homework->title,
            'created' => get_date_week(strtotime($this->homework->created_at)),
            'content' => mb_strlen($content,'UTF8')<=10?$content:$content.'....',
            'month' => date('m'),
            'date' => date('d'),
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

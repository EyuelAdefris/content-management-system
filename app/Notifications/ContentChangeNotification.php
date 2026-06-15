<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ContentChangeNotification extends Notification
{
    use Queueable;

    protected $modelType;
    protected $action;
    protected $title;
    protected $authorName;

    public function __construct(string $modelType, string $action, string $title, string $authorName)
    {
        $this->modelType = $modelType;
        $this->action = $action;
        $this->title = $title;
        $this->authorName = $authorName;
    }

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toArray($notifiable): array
    {
        return [
            'title' => "{$this->modelType} {$this->action}: {$this->title}",
            'author_name' => $this->authorName,
            'action_text' => "{$this->modelType} {$this->action} by ",
            'created_at' => now()->toIso8601String(),
        ];
    }
}

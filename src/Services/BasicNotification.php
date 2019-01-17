<?php
/**
 * Created by PhpStorm.
 * User: alexey
 * Date: 12.08.18
 * Time: 17:23
 */

namespace ARudkovskiy\Admin\Services;


use ARudkovskiy\Admin\Contracts\Notification;

class BasicNotification implements Notification
{

    protected $type;

    protected $icon;

    protected $message;

    public function getType(): string
    {
        return $this->type;
    }

    public function getIcon(): string
    {
        return $this->icon;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

}
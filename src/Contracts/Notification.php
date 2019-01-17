<?php
/**
 * Created by PhpStorm.
 * User: alexey
 * Date: 12.08.18
 * Time: 17:22
 */

namespace ARudkovskiy\Admin\Contracts;


interface Notification
{

    public function getType(): string;

    public function getIcon(): string;

    public function getMessage(): string;

}
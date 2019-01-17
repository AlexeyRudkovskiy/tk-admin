<?php
/**
 * Created by PhpStorm.
 * User: alexey
 * Date: 07.09.18
 * Time: 23:26
 */

namespace ARudkovskiy\Admin\Traits;


use ARudkovskiy\Admin\Models\Route;
use Illuminate\Database\Eloquent\Model;

trait Routable
{

    public function route() {
        return $this->morphOne(Route::class, 'routable');
    }

    public function hasRoute() {
        return $this->route !== null;
    }

    public abstract function getController(): string;

    public abstract function getAction(): string;

    public abstract function getRoutedUrl(): string;

}
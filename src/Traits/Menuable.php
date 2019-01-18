<?php
/**
 * Created by PhpStorm.
 * User: alexey
 * Date: 26.08.18
 * Time: 19:37
 */

namespace ARudkovskiy\Admin\Traits;


use ARudkovskiy\Admin\Models\Menu;

trait Menuable
{

    public function menus() {
        return $this->morphToMany(Menu::class, 'menuable');
    }

    public abstract function getText(): string;

    public abstract function getUrl(): string;

}
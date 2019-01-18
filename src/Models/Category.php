<?php
/**
 * Created by PhpStorm.
 * User: alexey
 * Date: 26.08.18
 * Time: 04:26
 */

namespace ARudkovskiy\Admin\Models;


use Illuminate\Database\Eloquent\Model;

class Category extends Model
{

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function menus()
    {
        return $this->morphedByMany(Menu::class, 'categoriable');
    }

    public function morphedRecords($type)
    {
        return $this->morphedByMany($type, 'categoriable');
    }

}
<?php
/**
 * Created by PhpStorm.
 * User: alexey
 * Date: 07.09.18
 * Time: 23:26
 */

namespace ARudkovskiy\Admin\Models;


use Illuminate\Database\Eloquent\Model;

class Route extends Model
{

    protected $fillable = [
        'url',
        'action'
    ];

    public function routable()
    {
        return $this->morphTo();
    }

}
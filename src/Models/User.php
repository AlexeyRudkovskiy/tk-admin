<?php
/**
 * Created by PhpStorm.
 * User: alexey
 * Date: 11.08.18
 * Time: 16:52
 */

namespace ARudkovskiy\Admin\Models;


use Illuminate\Database\Eloquent\Model;

class User extends Model
{

    protected $hidden = [
        'password'
    ];

    protected $fillable = [
        'username', 'email', 'password', 'full_name'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

}
<?php
/**
 * Created by PhpStorm.
 * User: alexey
 * Date: 08.08.18
 * Time: 00:12
 */

namespace ARudkovskiy\Admin\Models;


use ARudkovskiy\Admin\Contracts\Entity;
use ARudkovskiy\Admin\Traits\Menuable;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{

    protected $casts = [
        'items' => 'array'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function categories()
    {
        return $this->morphToMany(Category::class, 'categoriable');
    }

    public function posts()
    {
        return $this->morphedByMany('\App\Post', 'menuable');
    }

    public function menuableRelation($target)
    {
        return $this->morphedByMany($target, 'menuable');
    }

    public function getLinkInfo($item) {
        \Log::info($item);
        return [
            'url' => $item['url'],
            'text' => $item['text']
        ];
    }

    public function updateItem($type, $object)
    {
        $items = $this->items ?? [];
        foreach ($items as $key => $item) {
            if ($item['type'] === $type && $item['id'] === $object->id) {
                $item['url'] = $object->getUrl();
                $item['text'] = $object->getText();
                $item['title'] = $item['text'];
                $items[$key] = $item;
            }
        }
        $this->items = $items;
    }

}
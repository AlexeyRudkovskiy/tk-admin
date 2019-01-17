<?php
/**
 * Created by PhpStorm.
 * User: alexey
 * Date: 07.09.18
 * Time: 23:18
 */

namespace ARudkovskiy\Admin\Events;


use ARudkovskiy\Admin\Contracts\Entity;

class EntitySaved
{

    /**
     * @var Entity
     */
    protected $entity = null;

    public function __construct(Entity $entity)
    {
        $this->entity = $entity;
    }

    /**
     * @return Entity
     */
    public function getEntity(): Entity
    {
        return $this->entity;
    }

    /**
     * @param Entity $entity
     */
    public function setEntity(Entity $entity)
    {
        $this->entity = $entity;
    }

}
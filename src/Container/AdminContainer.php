<?php
/**
 * Created by PhpStorm.
 * User: alexey
 * Date: 20.07.18
 * Time: 22:28
 */

namespace ARudkovskiy\Admin\Container;


use ARudkovskiy\Admin\Container\AdminContainerInterface;
use ARudkovskiy\Admin\Contracts\Entity;
use Illuminate\Support\Collection;

class AdminContainer implements AdminContainerInterface
{

    /**
     * @var Collection
     */
    private $entities;

    private $user;

    public function __construct()
    {
        $this->entities = collect([]);
    }

    public function registerEntity(Entity $entity)
    {
        if ($entity->validateRequest('any')) {
            $this->entities->push($entity);
        }
    }

    /**
     * If $section equals to null, all entities will be returned
     *
     * @param string $section
     * @return Collection
     */
    public function getEntities($section = 'default'): Collection
    {
        if ($section === null) {
            return $this->entities;
        }

        return $this->entities
            ->filter(function (Entity $entity) use ($section) {
                return $entity->getSection() === $section;
            });
    }

    public function getEntity(string $shortName): Entity
    {
        return $this->entities
            ->filter(function (Entity $entity) use ($shortName) {
                return $entity->getShortName() === $shortName;
            })
            ->first();
    }

    public function setUser($user)
    {
        // TODO: Implement setUser() method.
    }
}
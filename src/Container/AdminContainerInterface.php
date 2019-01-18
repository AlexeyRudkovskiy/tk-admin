<?php
/**
 * Created by PhpStorm.
 * User: alexey
 * Date: 20.07.18
 * Time: 22:28
 */

namespace ARudkovskiy\Admin\Container;


use ARudkovskiy\Admin\Contracts\Entity;
use Illuminate\Support\Collection;

interface AdminContainerInterface
{

    public function registerEntity(Entity $entity);

    public function getEntities($section = 'default'): Collection;

    public function getEntity(string $shortName): Entity;

    public function setUser($user);

}
<?php
/**
 * Created by PhpStorm.
 * User: alexey
 * Date: 07.09.18
 * Time: 23:18
 */

namespace ARudkovskiy\Admin\Listeners;


use ARudkovskiy\Admin\Events\EntitySaved;
use ARudkovskiy\Admin\Models\Route;
use ARudkovskiy\Admin\Traits\Routable;

class EntitySavedListener
{

    public function handle(EntitySaved $entitySaved)
    {
        $entity = $entitySaved->getEntity();
        if (!$entity->isRoutable() && !in_array(Routable::class, class_uses($entity->getObject()))) {
            return;
        }

        /** @var Routable $entityObject */
        $entityObject = $entity->getObject();
        $action = [ $entityObject->getController(), $entityObject->getAction() ];
        $action = implode('@', $action);
        $params = [
            'url' => $entityObject->getRoutedUrl(),
            'action' => $action
        ];
        if (!$entityObject->hasRoute()) {
            $routerRule = $entityObject->route()->save(new Route($params));
        } else {
            $route = $entityObject->route;
            $route->update($params);
            $routerRule = $route;
        }
    }

}
<?php
/**
 * Created by PhpStorm.
 * User: alexey
 * Date: 25.08.18
 * Time: 21:17
 */

namespace ARudkovskiy\Admin\EntityFields;


use ARudkovskiy\Admin\Container\AdminContainerInterface;
use ARudkovskiy\Admin\Contracts\Entity;
use ARudkovskiy\Admin\Contracts\EntityField;
use ARudkovskiy\Admin\Models\Category;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Http\Request;

class MenuField extends EntityField
{

    protected $template = '@admin::form.widget.menu';

    public function __construct()
    {
        parent::__construct();
        $this->setIsUpdatingManually(true);
        $this->setHandleAfterSave(true);
    }

    public function renderEditable()
    {
        $adminContainer = app()->make(AdminContainerInterface::class);
        $menuable = $adminContainer
            ->getEntities()
            ->filter(function (Entity $entity) {
                return $entity->isMenuable();
            })
            ->values()
            ->map(function (Entity $entity) {
                return [
                    'name' => $entity->getShortName(),
                    'title' => $entity->translate('menuable'),
                    'id' => $entity->getUniqueIdentifier()
                ];
            });

        return parent::renderEditable()
            ->with('items', json_encode($this->value ?? []))
            ->with('menuable', $menuable);
    }


    public function handleRequest(Request $request, $entityObject)
    {
        $items = $request->get('items');
        $items = json_decode($items);

        $baseAppUrl = url('/');
        foreach ($items as $item) {
            if (property_exists($item, 'url')) {
                $item->url = str_replace($baseAppUrl, '', $item->url);
            }
        }

        $menuable = $this->getMenuableEntities();
        $menuableRelations = [];
        $menuableSyncData = [];

        foreach ($menuable as $key => $className) {
            $menuableRelations[$key] = $this->getEntity()->getObject()->menuableRelation($className);
        }

        foreach ($items as $key => $item) {
            if (!array_key_exists($item->type, $menuable)) {
                continue;
            }

            if (!array_key_exists($item->type, $menuableSyncData)) {
                $menuableSyncData[$item->type] = [];
            }

            array_push($menuableSyncData[$item->type], $item->id);

            // todo: Optimize menu building
            $model = (new $menuable[$item->type])->find($item->id);
            $item->url = $model->getUrl();
            $item->text = $model->getText();

            if (property_exists($item, 'url')) {
                $item->url = str_replace($baseAppUrl, '', $item->url);
            }

            $items[$key] = $item;
        }

        foreach ($menuableSyncData as $key => $syncData) {
            $menuableRelations[$key]->sync($syncData, true);
        }

        $this->value = $items;
        $entityObject->{$this->name} = $items;
    }

    public function getMenuableEntities()
    {
        $adminContainer = app()->make(AdminContainerInterface::class);

        return $adminContainer
            ->getEntities(null)
            ->filter(function (Entity $entity) {
                return $entity->isMenuable();
            })
            ->mapWithKeys(function (Entity $entity) {
                return [ $entity->getShortName() => $entity->getEntityClass() ];
            })
            ->toArray();
    }

    public function getDefaultValue()
    {
        return $this->getValue() ?? [];
    }

}
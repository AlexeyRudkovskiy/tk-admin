<?php
/**
 * Created by PhpStorm.
 * User: alexey
 * Date: 08.08.18
 * Time: 00:15
 */

namespace ARudkovskiy\Admin\Entities;


use ARudkovskiy\Admin\Contracts\AbstractEntity;
use ARudkovskiy\Admin\EntityFields\AddMenuItemField;
use ARudkovskiy\Admin\EntityFields\CategoriesField;
use ARudkovskiy\Admin\EntityFields\EnumField;
use ARudkovskiy\Admin\EntityFields\IdField;
use ARudkovskiy\Admin\EntityFields\MenuField;
use ARudkovskiy\Admin\EntityFields\NumberField;
use ARudkovskiy\Admin\EntityFields\TextField;
use ARudkovskiy\Admin\Models\Menu;
use ARudkovskiy\Admin\Traits\Menuable;
use Illuminate\Database\Schema\PostgresBuilder;

class MenuEntity extends AbstractEntity
{

    protected $entityClass = Menu::class;

    public function registerFields(): array
    {
        return [
            IdField::create('id'),
            TextField::create('name')
                ->showInIndexTable()
                ->setOrderInIndexTable(1),
            MenuField::create('items')
                ->setOptions([
                    'id' => implode('_', [ 'menu', 'items', $this->getUniqueIdentifier() ])
                ]),
            CategoriesField::create('categories')
                ->setOptions([
                    'location' => 'sidebar'
                ]),
            EnumField::create('location', null, function ($value) {
                return trans('@admin::dashboard.entity.menu.locations.' . $value);
            })
                ->setOptions([ 'location' => 'sidebar' ])
                ->setElements([
                    'header' => trans('@admin::dashboard.entity.menu.locations.header'),
                    'left-sidebar' => trans('@admin::dashboard.entity.menu.locations.left-sidebar'),
                    'right-sidebar' => trans('@admin::dashboard.entity.menu.locations.right-sidebar')
                ])
                ->showInIndexTable()
                ->setOrderInIndexTable(2)
                ->setWidth(175),
            NumberField::create('order')
                ->setOptions([ 'location' => 'sidebar' ])
                ->setDefault(100)
                ->showInIndexTable()
                ->setWidth(125)
                ->setOrderInIndexTable(3),
            TextField::create('tag')
                ->setOptions([ 'location' => 'sidebar' ])
        ];
    }

    public function getTranslations(): array
    {
        return trans('@admin::dashboard.entity.menu');
    }

    public function getIcon(): string
    {
        return 'fa fa-link';
    }

    public function validateRequest(string $type): bool
    {
        return true;
    }

    public function getSection(): string
    {
        return 'system';
    }

    public function delete()
    {
        /** @var Menu $menu */
        $menu = $this->getObject();
        $menu->posts()->sync([]);
//        $menu->posts->map(function ($object) {
//            $object->menu_id = 0;
//        })
//            ->each(function ($object) {
//                $object->save();
//            });

        parent::delete();
    }

}
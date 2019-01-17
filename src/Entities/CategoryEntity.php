<?php
/**
 * Created by PhpStorm.
 * User: alexey
 * Date: 26.08.18
 * Time: 04:29
 */

namespace ARudkovskiy\Admin\Entities;


use ARudkovskiy\Admin\Contracts\AbstractEntity;
use ARudkovskiy\Admin\EntityFields\IdField;
use ARudkovskiy\Admin\EntityFields\TextField;
use ARudkovskiy\Admin\Models\Category;

class CategoryEntity extends AbstractEntity
{

    protected $entityClass = Category::class;

    public function registerFields(): array
    {
        return [
            IdField::create('id'),
            TextField::create('name')
                ->showInIndexTable()
                ->setOrderInIndexTable(1)
        ];
    }

    public function getTranslations(): array
    {
        return trans('@admin::dashboard.entity.category');
    }

    public function validateRequest(string $type): bool
    {
        return true;
    }

    public function getSection(): string
    {
        return 'system';
    }

}
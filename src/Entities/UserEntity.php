<?php
/**
 * Created by PhpStorm.
 * User: alexey
 * Date: 11.08.18
 * Time: 17:00
 */

namespace ARudkovskiy\Admin\Entities;


use ARudkovskiy\Admin\Contracts\AbstractEntity;
use ARudkovskiy\Admin\EntityFields\EmailField;
use ARudkovskiy\Admin\EntityFields\IdField;
use ARudkovskiy\Admin\EntityFields\PasswordField;
use ARudkovskiy\Admin\EntityFields\TextField;
use ARudkovskiy\Admin\Models\User;

class UserEntity extends AbstractEntity
{

    protected $entityClass = User::class;

    public function registerFields(): array
    {
        return [
            IdField::create('id'),
            TextField::create('username')
                ->showInIndexTable()
                ->setOrderInIndexTable(1),
            TextField::create('full_name'),
            EmailField::create('email')
                ->showInIndexTable()
                ->setWidth(300)
                ->setOrderInIndexTable(2),
            PasswordField::create('password')
        ];
    }

    public function getTranslations(): array
    {
        return trans('@admin::dashboard.entity.user');
    }

    public function validateRequest(string $type): bool
    {
        return true;
    }

    public function getIcon(): string
    {
        return 'fa fa-user';
    }

    public function getSection(): string
    {
        return 'system';
    }

}
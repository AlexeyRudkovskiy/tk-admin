<?php
/**
 * Created by PhpStorm.
 * User: alexey
 * Date: 20.07.18
 * Time: 22:38
 */

namespace ARudkovskiy\Admin\Contracts;

use ARudkovskiy\Admin\EntityFields\FileField;
use ARudkovskiy\Admin\EntityFields\IdField;
use ARudkovskiy\Admin\EntityFields\SimpleRelationField;
use ARudkovskiy\Admin\Events\EntitySaved;
use ARudkovskiy\Admin\Exceptions\EntityClassIsUndefined;
use ARudkovskiy\Admin\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

abstract class AbstractEntity implements Entity
{

    protected $entityClass = null;

    /** @var string */
    protected $shortName = null;

    /** @var string  */
    protected $icon = 'fa fa-file';

    /** @var string */
    protected $uniqueIdentifier = null;

    /** @var bool  */
    protected $menuable = false;

    /** @var bool  */
    protected $routable = false;

    /** @var Collection */
    private $fields = null;

    /** @var int */
    private $id;

    private $entityObject = null;

    /**
     * AbstractEntity constructor.
     */
    public function __construct()
    {
        $this->generateUniqueIdentifier();
    }

    public function getEntityClass(): string
    {
        return $this->entityClass;
    }

    public function getFields(): Collection
    {
        if ($this->fields === null) {
            $this->fields = collect($this->registerFields());
            $this->fields->each(function (EntityField $entityField) {
                $entityField->setEntity($this);
            });
        }

        return $this->fields;
    }

    public function getAdditionalTableColumns(): array
    {
        return [];
    }

    public function getShortName(): string
    {
        if ($this->shortName !== null) {
            return $this->shortName;
        }

        if ($this->entityClass !== null) {
            $entityClassParts = explode('\\', $this->entityClass);
            $entityClassParts = array_pop($entityClassParts);
            $entityClassParts = strtolower($entityClassParts);

            $this->shortName = $entityClassParts;
        } else {
            throw new EntityClassIsUndefined("\$entityClass is undefined. You should define it before use this method");
        }

        return $this->shortName;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getIcon(): string
    {
        return $this->icon;
    }

    public function getObject()
    {
        return $this->entityObject;
    }

    public function getField(string $fieldName)
    {
        return $this->getFields()
            ->first(function (EntityField $entityField) use ($fieldName) {
                return $entityField->getName() === $fieldName;
            });
    }

    public function process($object)
    {
        $this->entityObject = $object;

        $fields = $this->getFields();
        $data = $object;
        if (method_exists($object, 'toArray')) {
            $data = $object->toArray();
        }

        $this->id = $data['id'];

        /** @var EntityField $field */
        foreach ($fields as $field) {
            if ($field->isShouldIgnoreOnUpdate()) {
                continue;
            }
            $fieldName = $field->getName();
            if ($field instanceof SimpleRelationField) {
                try {
                    $value = $data[$field->getLocalField()];
                } catch (\Exception $e) {
                    dd($data, $field, $field->getLocalField());
                }
            } else if ($field->isUpdatingManually()) {
                $updatableField = $field->getManuallyUpdatableField();
//                if ($field instanceof FileField) {
//                    dd($updatableField, $object->{$field->getName()});
//                }
                if ($field->getName() === 'categories') {
//                    dd($updatableField);
                }
                $value = $updatableField === null ?
                    $object->{$field->getName()} :
                    (
                    $object->{$field->getName()} !== null ?
                        $object->{$field->getName()}->{$updatableField} :
                        null
                    );
            } else {
                $value = array_has($data, $fieldName) ? array_get($data, $fieldName) : null;
            }
            $field->process($value);
        }
    }

    public function handleRequest(Request $request, bool $isAfterSave = false)
    {
        $fields = $this->getFields();

        if ($this->entityObject === null) {

        }

        /** @var EntityField $field */
        foreach ($fields as $field) {
            if ($field instanceof IdField || $field->isShouldIgnoreOnUpdate()) {
                continue;
            }
            if ($field->isHandleAfterSave() && !$isAfterSave) {
                $defaultValue = $field->getDefaultValue();
                if ($defaultValue !== null) {
                    $this->entityObject->{$field->getName()} = $defaultValue;
                }
                continue;
            }
            $field->handleRequest($request, $this->entityObject);
            if ($field->isUpdatingManually()) {
                continue;
            }

            try {
                $this->entityObject->{$field->getName()} = $field->getValue();
            } catch (\Exception $e) {
                echo $e->getMessage();
                dd($field, $request, $this);
            }
        }
    }

    /**
     * @param string $key
     * @return string
     */
    public function translate(string $key): string
    {
        $translations = $this->getTranslations();
        if (array_has($translations, $key)) {
            return array_get($translations, $key);
        }
        return $key;
    }

    public function getSection(): string
    {
        return 'default';
    }

    public function getCustomFormView(): string {
        return null;
    }

    public function getUniqueIdentifier(): string {
        return $this->uniqueIdentifier;
    }

    public function isMenuable(): bool
    {
        return $this->menuable;
    }

    public function isRoutable(): bool
    {
        return $this->routable;
    }

    public function create()
    {
        $entityClass = $this->getEntityClass();
        $this->entityObject = new $entityClass();
        return $this->entityObject;
    }

    public function createAndSave()
    {
        $entityClass = $this->getEntityClass();
        $entity = new $this;
        $entity->entityObject = new $entityClass();
        return $entity;
    }

    public function save()
    {
        $saveResult = $this->entityObject->save();
        $this->handleRequest(request(), true);
        $saveResult = $this->entityObject->save();

        event(new EntitySaved($this));

        if ($this->isMenuable()) {
            $this->getObject()->menus()
                ->get()
                ->each(function (Menu $menu) {
                    $menu->updateItem($this->getShortName(), $this->getObject());
                    $menu->save();
                });
        }

        return $saveResult;
    }

    public function delete()
    {
        if ($this->entityObject !== null) {
            $this->entityObject->delete();
        }
    }

    public function fromObject($object)
    {
        /** @var Entity $entity */
        $entity = new $this;
        $entity->process($object);
        return $entity;
    }

    public function __get($name)
    {
        return $this->getObject()->{$name};
    }

    public function __set($name, $value)
    {
        $this->getObject()->{$name} = $value;
    }

    protected function generateUniqueIdentifier() {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        $length = 5;

        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }

        $this->uniqueIdentifier = $randomString;
    }

}

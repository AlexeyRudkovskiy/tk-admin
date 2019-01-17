<?php
/**
 * Created by PhpStorm.
 * User: alexey
 * Date: 03.08.18
 * Time: 18:50
 */

namespace ARudkovskiy\Admin\EntityFields;


use ARudkovskiy\Admin\Contracts\EntityField;
use Illuminate\Http\Request;

class SimpleRelationField extends EntityField
{

    protected $record = null;

    protected $template = '@admin::form.widget.relation';

    protected $tableTemplate = '@admin::table.columns.simple_relation';

    /**
     * SimpleRelationField constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->setIsUpdatingManually(true);
    }

    public function renderEditable()
    {
        $relatedModel = $this->getRelatedModel()->query();
        $rules = $this->getOption('config.rules');

        if ($rules !== null) {
            $relatedModel = call_user_func($rules, $relatedModel);
        }

        $value = null;
        $foreignValue = null;

        if ($this->getOption('select2') === null) {
            $relatedModel = $relatedModel->get();
        } else {
            $relatedFieldName = $this->getOption('config.entity');
            if ($this->getEntity()->getObject() !== null && $this->getEntity()->getObject()->id !== null) {
                $value = $this->getEntity()->getObject()->{$relatedFieldName};
                if ($value !== null) {
                    $foreignValueName = $this->getOption('config.foreign_field');
                    $foreignValue = $value->{$foreignValueName};
                }
            }
        }

        return parent::renderEditable()
            ->with('relation_data', $relatedModel)
            ->with('foreign_field', $this->getForeignField())
            ->with('foreign_key', $this->getForeignKey())
            ->with('value', $this->getValue())
            ->with('select2_value', $value->id ?? null)
            ->with('select2_text', $foreignValue ?? null);
    }

    public function handleRequest(Request $request, $entityObject)
    {
        $relatedId = $request->get($this->name);
        $localRelationMethod = $this->getOption('config.local_relation');
        if ($localRelationMethod === null) {
            $localRelationMethod = $this->name;
        }

        if (method_exists($entityObject, $localRelationMethod)) {
            $entityObject->{$localRelationMethod}()->associate($relatedId);
        } else {
            $entityObject->{$this->getLocalField()} = $relatedId;
        }

        $this->value = $relatedId;

        return null;
    }

    public function process($value)
    {
        $this->value = $value;
    }

    public function findRelatedObject() {
        $model = $this->getRelatedModel();
        $localField = $this->getLocalField();

        return $model
            ->where('id', $this->record->{$localField})
            ->first();
    }

    public function getRelatedField() {
        $relatedObject = $this->findRelatedObject();
        $foreignField = $this->getForeignField();
        return $relatedObject->{$foreignField};
    }

    public function getForeignKey(): string {
        return $this->getOption('config.foreign_key') ?? 'id';
    }

    public function getForeignField(): string {
        return $this->getOption('config.foreign_field');
    }

    public function getLocalField(): string {
        $localField = $this->getOption('config.local_field');

        if ($localField === null) {
            $localField = $this->name . '_id';
        }

        return $localField;
    }

    protected function getRelatedModel() {
        $model = $this->getOption('config.model');
        return new $model();
    }

}

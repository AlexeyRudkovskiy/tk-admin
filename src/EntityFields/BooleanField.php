<?php
/**
 * Created by PhpStorm.
 * User: alexey
 * Date: 10.09.18
 * Time: 01:24
 */

namespace ARudkovskiy\Admin\EntityFields;


use ARudkovskiy\Admin\Contracts\EntityField;
use Illuminate\Http\Request;

class BooleanField extends TextField
{

    protected $type = 'checkbox';

    public function renderEditable()
    {
        return parent::renderEditable()
            ->with('checked', $this->isChecked());
    }

    public function renderTableColumn($value, $record)
    {
        return view('@admin::table.columns.boolean')
            ->with('checked', $this->isChecked())
            ->with('record_id', $record->id)
            ->with('name', $this->getName());
    }

    public function handleRequest(Request $request, $entityObject)
    {
        $updatedValue = $request->has($this->name);
        $this->value = $updatedValue;
    }

    public function isChecked() {
        return $this->getEntity()->getObject()->{$this->getName()};
    }

    public static function create(string $name, string $label = null, $view = null)
    {
        $instance = parent::create($name, $label, $view);
        $instance->setView([ $instance, 'renderTableColumn' ]);
        return $instance;
    }

}
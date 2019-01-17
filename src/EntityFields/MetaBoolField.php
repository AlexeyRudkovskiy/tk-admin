<?php
/**
 * Created by PhpStorm.
 * User: alexey
 * Date: 21.12.18
 * Time: 14:57
 */

namespace ARudkovskiy\Admin\EntityFields;


use Illuminate\Http\Request;

class MetaBoolField extends BooleanField
{

    public function __construct()
    {
        parent::__construct();
        $this->setIsUpdatingManually(true);
        $this->setHandleAfterSave(true);
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
        if ($updatedValue) {
            $this->getEntity()->getObject()->metas()->create([
                'key' => $this->name,
                'value' => 1
            ]);
        } else {
            if ($entityObject->id > 0) {
                $meta = $this->getEntity()->getObject()->meta($this->name);
                if ($meta !== null) {
                    $meta->delete();
                }
            }
        }
    }

    public function isChecked() {
        $entityObject = $this->getEntity()->getObject();
        $metaName = $this->getName();

        $metaValue = $entityObject->meta($metaName);
        return $metaValue !== null;
    }

}
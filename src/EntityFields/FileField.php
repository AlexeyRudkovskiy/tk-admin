<?php
/**
 * Created by PhpStorm.
 * User: alexey
 * Date: 04.08.18
 * Time: 21:09
 */

namespace ARudkovskiy\Admin\EntityFields;


use ARudkovskiy\Admin\Contracts\EntityField;
use ARudkovskiy\Admin\Models\File;
use Illuminate\Http\Request;

class FileField extends EntityField
{

    protected $template = '@admin::form.widget.file';

    /**
     * FileField constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->setIsUpdatingManually(true);
    }

    public function renderEditable()
    {
        $file = File::find($this->value);
        return parent::renderEditable()
            ->with('file', $file);
    }


    public function handleRequest(Request $request, $entityObject)
    {
        $fieldName = $this->getName();
        $fileId = $request->get($fieldName);

        if ($fileId === null) {
            $entityObject->{$fieldName}()->dissociate();
        } else {
            $entityObject->{$fieldName}()->associate($fileId);
        }
    }

    public function getManuallyUpdatableField()
    {
        return 'id';
    }


}
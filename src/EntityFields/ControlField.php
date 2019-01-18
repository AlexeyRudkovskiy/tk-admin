<?php
/**
 * Created by PhpStorm.
 * User: alexey
 * Date: 25.07.18
 * Time: 16:32
 */

namespace ARudkovskiy\Admin\EntityFields;


use ARudkovskiy\Admin\Contracts\EntityField;
use Illuminate\Http\Request;

class ControlField extends EntityField
{

    protected $template = '@admin::form.widget.control_field';

    public function handleRequest(Request $request, $entityObject)
    {
        // empty
    }

}
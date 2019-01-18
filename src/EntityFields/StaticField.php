<?php
/**
 * Created by PhpStorm.
 * User: alexey
 * Date: 07.08.18
 * Time: 00:41
 */

namespace ARudkovskiy\Admin\EntityFields;


use ARudkovskiy\Admin\Contracts\EntityField;

class StaticField extends EntityField
{

    protected $template = '@admin::form.widget.static';

    public function renderEditable()
    {
        $value = $this->render();
        return parent::renderEditable()
            ->with('value', $value);
    }

}
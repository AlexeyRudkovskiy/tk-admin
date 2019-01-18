<?php
/**
 * Created by PhpStorm.
 * User: alexey
 * Date: 30.09.18
 * Time: 19:16
 */

namespace ARudkovskiy\Admin\EntityFields;


use ARudkovskiy\Admin\Contracts\EntityField;

class TextAreaField extends EntityField
{

    protected $template = '@admin::form.widget.text_area';

}
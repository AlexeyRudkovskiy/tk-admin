<?php
/**
 * Created by PhpStorm.
 * User: alexey
 * Date: 01.10.18
 * Time: 19:33
 */

namespace ARudkovskiy\Admin\EntityFields;


use ARudkovskiy\Admin\Contracts\EntityField;

class EnumField extends EntityField
{

    /**
     * @var array
     */
    protected $elements = [];

    protected $template = '@admin::form.widget.enum';

    public function renderEditable()
    {
        return parent::renderEditable()
            ->with('elements', $this->elements);
    }

    public function setElements(array $element) {
        $this->elements = $element;
        return $this;
    }

}
<?php
/**
 * Created by PhpStorm.
 * User: alexey
 * Date: 21.07.18
 * Time: 02:08
 */

namespace ARudkovskiy\Admin\EntityFields;


use ARudkovskiy\Admin\Contracts\EntityField;

class TextField extends EntityField
{

    protected $template = '@admin::form.widget.text';

    protected $type = 'text';

    protected $default = null;

    public function renderEditable()
    {
        return parent::renderEditable()
            ->with('type', $this->type);
    }

    /**
     * @return null
     */
    public function getDefault()
    {
        return $this->default;
    }

    /**
     * @param null $default
     */
    public function setDefault($default)
    {
        $this->default = $default;
        return $this;
    }

}
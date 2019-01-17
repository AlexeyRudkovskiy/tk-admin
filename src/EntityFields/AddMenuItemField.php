<?php
/**
 * Created by PhpStorm.
 * User: alexey
 * Date: 25.08.18
 * Time: 22:30
 */

namespace ARudkovskiy\Admin\EntityFields;


use ARudkovskiy\Admin\Contracts\EntityField;

class AddMenuItemField extends EntityField
{

    protected $template = '@admin::form.widget.add_menu_item';

    public function __construct()
    {
        parent::__construct();
        $this->setIsUpdatingManually(true);
    }

}
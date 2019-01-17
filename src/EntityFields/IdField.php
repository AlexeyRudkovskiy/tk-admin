<?php
/**
 * Created by PhpStorm.
 * User: alexey
 * Date: 21.07.18
 * Time: 19:21
 */

namespace ARudkovskiy\Admin\EntityFields;


use ARudkovskiy\Admin\Contracts\EntityField;
use Illuminate\Http\Request;

class IdField extends EntityField
{

    protected $template = null;

    public function __construct()
    {
        parent::__construct();
        $this->setWidth(75);
    }

    public function handleRequest(Request $request, $entityObject)
    {
        // empty
    }

}
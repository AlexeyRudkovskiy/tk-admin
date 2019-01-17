<?php
/**
 * Created by PhpStorm.
 * User: alexey
 * Date: 30.09.18
 * Time: 19:15
 */

namespace ARudkovskiy\Admin\EntityFields;


use Illuminate\Http\Request;

class NumberField extends TextField
{

    protected $type = 'number';

    public function handleRequest(Request $request, $entityObject)
    {
        $updatedValue = $request->get($this->name);
        $updatedValue = $this->default !== null && empty($updatedValue) ? $this->default : $updatedValue;
        $this->value = $updatedValue;
    }

}
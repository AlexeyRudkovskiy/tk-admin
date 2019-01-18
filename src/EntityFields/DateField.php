<?php
/**
 * Created by PhpStorm.
 * User: alexey
 * Date: 23.08.18
 * Time: 14:47
 */

namespace ARudkovskiy\Admin\EntityFields;


use ARudkovskiy\Admin\Contracts\EntityField;
use Illuminate\Http\Request;

class DateField extends TextField
{

    protected $type = 'date';

}
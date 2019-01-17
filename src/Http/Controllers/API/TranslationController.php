<?php
/**
 * Created by PhpStorm.
 * User: alexey
 * Date: 23.08.18
 * Time: 22:45
 */

namespace ARudkovskiy\Admin\Http\Controllers\API;


use ARudkovskiy\Admin\Http\Controllers\Controller;

class TranslationController extends Controller
{

    public function translation()
    {
        return [
            'dashboard' => trans('@admin::dashboard')
        ];
    }

}
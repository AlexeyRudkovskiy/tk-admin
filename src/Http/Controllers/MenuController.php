<?php
/**
 * Created by PhpStorm.
 * User: alexey
 * Date: 25.08.18
 * Time: 21:12
 */

namespace ARudkovskiy\Admin\Http\Controllers;


class MenuController extends Controller
{

    public function index()
    {
        return view('@admin::menu.index');
    }

}
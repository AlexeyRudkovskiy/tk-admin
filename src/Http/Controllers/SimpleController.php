<?php
/**
 * Created by PhpStorm.
 * User: alexey
 * Date: 20.07.18
 * Time: 21:58
 */

namespace ARudkovskiy\Admin\Http\Controllers;


use ARudkovskiy\Admin\Container\AdminContainerInterface;
use ARudkovskiy\Admin\EntityFields\TextField;

class SimpleController extends Controller
{

    public function indexAction(AdminContainerInterface $adminContainer) {
        return view('@admin::layout/dashboard', [
            'title' => "Index action",
            'admin' => $adminContainer
        ]);
    }

}
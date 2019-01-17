<?php
/**
 * Created by PhpStorm.
 * User: alexey
 * Date: 25.07.18
 * Time: 16:07
 */

namespace ARudkovskiy\Admin\Http;


use ARudkovskiy\Admin\Container\AdminContainerInterface;
use ARudkovskiy\Admin\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Routing\Route;

class DashboardViewComposer
{

    /** @var AdminContainerInterface */
    protected $adminContainer;

    /** @var Request */
    protected $request;

    /**
     * DashboardViewComposer constructor.
     */
    public function __construct(AdminContainerInterface $adminContainer, Request $request)
    {
        $this->adminContainer = $adminContainer;
        $this->request = $request;
    }


    public function compose(View $view) {
        $entityName = $this->request->get('entity', null);
        /** @var Route $currentRoute */
        $currentRoute = \Route::getCurrentRoute();
        $actionMethod = $currentRoute->getActionMethod();

        $view->with('admin', $this->adminContainer);
        $view->with('entity_name', $entityName);

        $administrator = User::find(session()->get('user_id'));

        $view->with('administrator', $administrator);

        if ($entityName !== null) {
            $entity = $this->adminContainer->getEntity($entityName);

            $view->with('title', $entity->translate('title.' . $actionMethod));
            $view->with('entity', $entity);
        } else {
            $routeName = $currentRoute->getName();
            $routeName = preg_replace('/^admin/', '', $routeName);
            $view->with('title', trans('@admin::dashboard.title' . $routeName));
            $view->with('entity', null);
        }
    }

}
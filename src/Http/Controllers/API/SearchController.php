<?php
/**
 * Created by PhpStorm.
 * User: alexey
 * Date: 29.08.18
 * Time: 23:58
 */

namespace ARudkovskiy\Admin\Http\Controllers\API;


use ARudkovskiy\Admin\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SearchController extends Controller
{

    public function index(Request $request)
    {
        $adminContainer = app()->make(\ARudkovskiy\Admin\Container\AdminContainerInterface::class);
        $entityName = $request->get('entity');
        if ($entityName === null) {
            return [];
        }
        $entity = $adminContainer->getEntity($entityName);
        $entityClass = $entity->getEntityClass();
        $searchRequest = $request->get('query');

        return (new $entityClass)
            ->where('title', 'like', '%' . $searchRequest . '%')
            ->select('id', 'title')
            ->get()
            ->map(function ($record) {
                $record->title = $record->title . ' (' . $record->id . ')';
                return $record;
            });
    }

}
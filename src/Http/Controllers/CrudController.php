<?php
/**
 * Created by PhpStorm.
 * User: alexey
 * Date: 21.07.18
 * Time: 02:59
 */

namespace ARudkovskiy\Admin\Http\Controllers;


use ARudkovskiy\Admin\Container\AdminContainerInterface;
use ARudkovskiy\Admin\Contracts\Entity;
use ARudkovskiy\Admin\Contracts\EntityField;
use ARudkovskiy\Admin\EntityFields\ControlField;
use ARudkovskiy\Admin\Services\Repository;
use Illuminate\Http\Request;
use Illuminate\Support\MessageBag;

class CrudController extends Controller
{

    protected $adminContainer;

    /** @var Entity */
    protected $entity = null;

    public function __construct(AdminContainerInterface $adminContainer, Request $request)
    {
        $this->adminContainer = $adminContainer;
        $entityShortName = $request->get('entity');
        if (strlen($entityShortName) > 0) {
            $this->entity = $this->adminContainer->getEntity($entityShortName);
        }
    }

    public function index(AdminContainerInterface $adminContainer, Request $request) {
        $repository = new Repository($this->entity);

        $additionalTableFields = collect($this->entity->getAdditionalTableColumns());

        $tableFields = collect($this->entity->getFields())
            ->filter(function (EntityField $entityField) {
                return $entityField->isShouldShowInIndexTable();
            })
            ->merge($additionalTableFields)
            ->sortBy(function (EntityField $entityField) {
                return $entityField->getOrderInIndexTable();
            })
            ->toArray();

        $onPage = config('admin.pagination.per_page');
        $currentPage = $request->get('page', 1);

        $records = $repository->paginate($onPage, $currentPage);

        return view('@admin::crud.index', [
            'title' => 'page title',
            'admin' => $adminContainer,
            'repository' => $repository,
            'fields' => $tableFields,
            'records' => $records,
            'entity' => $this->entity
        ]);
    }

    public function create(Request $request)
    {
        $record = $this->entity->create();

        return $this->renderView('@admin::crud.create', $this->entity, [
            'title' => 'Creating',
            'record' => $record
        ]);
    }

    public function edit(Request $request)
    {
        $repository = new Repository($this->entity);
        $repository->findByIdAndSaveInEntity($request->get('id'));

        view()->share('entity', $this->entity);

        $fields = $this->getFields($this->entity);

        return view('@admin::crud.edit', [
            'title' => 'Editing',
            'fields' => $fields
        ]);
    }

    public function update(Request $request)
    {
        $repository = new Repository($this->entity);
        $repository->findByIdAndSaveInEntity($request->get('id'));

        if ($request->has('delete')) {
            $this->entity->delete();

            $messages = [
                trans('@admin::dashboard.flash.crud.deleted')
            ];

            return redirect()
                ->route('admin.crud.index', [ 'entity' => $this->entity->getShortName() ])
                ->with('messages', $messages);
        } else {
            $this->entity->handleRequest($request);
            $this->entity->save();

            $messages = [
                trans('@admin::dashboard.flash.crud.updated')
            ];

            return redirect()
                ->route('admin.crud.edit', [ 'id' => $this->entity->getId(), 'entity' => $this->entity->getShortName() ])
                ->with('messages', $messages);
        }
    }

    public function save(Request $request)
    {
        $entityObject = $this->entity->create();

        $this->entity->handleRequest($request);
        $this->entity->save();

        $messages = [
            trans('@admin::dashboard.flash.crud.created')
        ];

        return redirect()->route('admin.crud.edit', [
            'entity' => $this->entity->getShortName(),
            'id' => $this->entity->getObject()->id
        ])->with('messages', $messages);
    }

    public function delete(Request $request)
    {
        $repository = new Repository($this->entity);
        $repository->findByIdAndSaveInEntity($request->get('id'));
        $this->entity->delete();
        return redirect()->back();
    }

    public function toggleBoolean(Request $request)
    {
        $repository = new Repository($this->entity);
        $repository->findByIdAndSaveInEntity($request->get('id'));
        $object = $this->entity->getObject();
        $fieldName = $request->get('field');
        $isChecked = $object->{$fieldName};

        $object->{$fieldName} = !$isChecked;
        $object->save();

        return trans('@admin::dashboard.general.' . (!$isChecked ? 'yes' : 'no'));
    }

    private function getFields(Entity $entity) {
        $fields = collect($entity->getFields());

        $fields = $fields
            ->groupBy(function (EntityField $entityField) {
                return $entityField->getOption('location');
            });

        if (!$fields->has('sidebar')) {
            $fields->put('sidebar', collect([]));
        }

        if (!$fields->has('content')) {
            $fields->put('content', collect([]));
        }

        $fields->get('sidebar')->push(ControlField::create('control'));

        return $fields;
    }

    private function renderView(string $viewName, Entity $entity, array $arguments) {
        view()->share('entity', $entity);

        return view($viewName, array_merge($arguments, [
            'entity' => $entity,
            'fields' => $this->getFields($entity)
        ]));
    }

}
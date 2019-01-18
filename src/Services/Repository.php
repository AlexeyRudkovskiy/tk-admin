<?php
/**
 * Created by PhpStorm.
 * User: alexey
 * Date: 21.07.18
 * Time: 03:27
 */

namespace ARudkovskiy\Admin\Services;


use ARudkovskiy\Admin\Contracts\Entity;
use Illuminate\Support\Collection;

class Repository
{

    /** @var Entity */
    private $entity;

    public function __construct(Entity $entity)
    {
        $this->entity = $entity;
    }

    public function create()
    {
        $entityClass = $this->entity->getEntityClass();
        $entityObject = new $entityClass();

        return new $entityObject;
    }

    public function findAll(): Collection {
        $entityClass = $this->entity->getEntityClass();
        $tempObject = new $entityClass;
        $objects = $tempObject->all();
        $objects = $objects->map(function ($object) {
            return $this->entity->fromObject($object);
        });
        return $objects;
    }

    public function findAllOrdered($orderBy, $orderType)
    {
        $entityClass = $this->entity->getEntityClass();
        $tempObject = new $entityClass;
        $objects = $tempObject->orderBy($orderBy, $orderType)->get();
        $objects = $objects->map(function ($object) {
            return $this->entity->fromObject($object);
        });
        return $objects;
    }

    public function findLatest()
    {
        $entityClass = $this->entity->getEntityClass();
        $tempObject = new $entityClass;
        $objects = $tempObject->latest()->get();
        $objects = $objects->map(function ($object) {
            return $this->entity->fromObject($object);
        });
        return $objects;
    }

    public function paginate($onPage, $currentPage = 1)
    {
        if ($currentPage < 1) {
            $currentPage = 1;
        }

        $entityClass = $this->entity->getEntityClass();
        $tempObject = new $entityClass;

        $objects = $tempObject->latest()
            ->limit($onPage)
            ->offset(($currentPage - 1) * $onPage)
            ->get();

        $objects = $objects->map(function ($object) {
            return $this->entity->fromObject($object);
        });
        $total = $tempObject->count();

        $paginator = (new Paginator($objects))
            ->setTotal($total)
            ->setPerPage($onPage)
            ->setPath(request()->url())
            ->setParameters(request()->all())
            ->setCurrentPage($currentPage);

        return $paginator;
    }

    public function findById($id)
    {
        $entityClass =$this->entity->getEntityClass();
        $tempObject = new $entityClass;
        return $tempObject->find($id);
    }

    public function findByIdAndSaveInEntity($id)
    {
        $this->entity->process($this->findById($id));
        return $this;
    }

    public function findLike($field, $query)
    {
        $entityClass = $this->entity->getEntityClass();
        $tempObject = new $entityClass;
        $objects = $tempObject->where($field, 'like', '%' . $query . '%')
            ->limit(15)
            ->get();

        return $objects;
    }

}
<?php
/**
 * Created by PhpStorm.
 * User: alexey
 * Date: 29.08.18
 * Time: 22:11
 */

namespace ARudkovskiy\Admin\Services;


use Illuminate\Support\Collection;
use Traversable;

class Paginator implements \IteratorAggregate
{

    /** @var Collection */
    protected $items;

    /** @var int  */
    protected $total = -1;

    /** @var int  */
    protected $perPage = -1;

    /** @var int  */
    protected $currentPage = -1;

    /** @var string  */
    protected $path = '';

    /** @var array  */
    protected $parameters = [];

    /** @var string */
    protected $view = '@admin::pagination.default';

    /** @var int  */
    protected $spread = 3;

    /**
     * Paginator constructor.
     * @param Collection $items
     */
    public function __construct(Collection $items)
    {
        $this->items = $items;
    }

    public function render()
    {
        $arguments = $this->toArray();
        $arguments = array_merge($arguments, [
            'links' => $this->generateLinks()
        ]);

        return view($this->view, $arguments);
    }

    /**
     * @return int
     */
    public function getTotal(): int
    {
        return $this->total;
    }

    /**
     * @param int $total
     * @return Paginator
     */
    public function setTotal(int $total)
    {
        $this->total = $total;
        return $this;
    }

    /**
     * @return int
     */
    public function getPerPage(): int
    {
        return $this->perPage;
    }

    /**
     * @param int $perPage
     * @return Paginator
     */
    public function setPerPage(int $perPage)
    {
        $this->perPage = $perPage;
        return $this;
    }

    /**
     * @return int
     */
    public function getCurrentPage(): int
    {
        return $this->currentPage;
    }

    /**
     * @param int $currentPage
     * @return Paginator
     */
    public function setCurrentPage(int $currentPage)
    {
        $this->currentPage = $currentPage;
        return $this;
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @param string $path
     * @return Paginator
     */
    public function setPath(string $path)
    {
        $this->path = $path;
        return $this;
    }

    /**
     * @return array
     */
    public function getParameters(): array
    {
        return $this->parameters;
    }

    /**
     * @param array $parameters
     * @return Paginator
     */
    public function setParameters(array $parameters)
    {
        $this->parameters = $parameters;
        return $this;
    }

    /**
     * @return int
     */
    public function getSpread(): int
    {
        return $this->spread;
    }

    /**
     * @param int $spread
     * @return Paginator
     */
    public function setSpread(int $spread)
    {
        $this->spread = $spread;
        return $this;
    }

    public function toArray()
    {
        return [
            'path' => $this->path,
            'parameters' => $this->parameters,
            'total' => $this->total,
            'perPage' => $this->perPage,
            'items' => $this->items,
            'currentPage' => $this->currentPage,
            'lastPage' => $this->getLastPage()
        ];
    }

    /**
     * Retrieve an external iterator
     * @link http://php.net/manual/en/iteratoraggregate.getiterator.php
     * @return Traversable An instance of an object implementing <b>Iterator</b> or
     * <b>Traversable</b>
     * @since 5.0.0
     */
    public function getIterator()
    {
        return $this->items->getIterator();
    }

    public function url($page)
    {
        if ($page === null) {
            return null;
        }

        $parameters = $this->parameters;
        $parameters = array_merge($parameters, [
            'page' => $page
        ]);

        $url = $this->path
            . (strpos($this->path, '?') === false ? '?' : '&')
            . http_build_query($parameters, '', '&');

        return $url;
    }

    protected function generateLinks()
    {
        return $this->getUrlRange();
    }

    protected function getUrlRange() {
        $lastPage = $this->getLastPage();
        $currentPage = $this->getCurrentPage();
        $spread = $this->spread;

        $startRange = range(1, $spread);
        $endRange = range($lastPage - $spread + 1, $lastPage);
        $middleRange = range($currentPage - $spread, $currentPage + $spread);

        return collect($startRange)
            ->merge($endRange)
            ->merge($middleRange)
            ->filter(function ($page) use ($lastPage) {
                return $page > 0 && $page <= $lastPage;
            })
            ->unique()
            ->sort()
            ->reduce(function ($total, $current) use ($lastPage, $spread) {
                if ($total->last() + 1 !== $current) {
                    return $total->push(null)->push($current);
                } else {
                    return $total->push($current);
                }
            }, collect([]))
            ->mapWithKeys(function ($page, $key) {
                if ($page === null) {
                    return [ $key + 1 => null ];
                }
                return [ $page => $this->url($page) ];
            });
    }

    protected function getLastPage() {
        return max((int) ceil($this->total / $this->perPage), 1);
    }

}
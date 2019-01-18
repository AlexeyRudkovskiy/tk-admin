<?php
/**
 * Created by PhpStorm.
 * User: alexey
 * Date: 25.07.18
 * Time: 15:52
 */

namespace ARudkovskiy\Admin\Services;


use ARudkovskiy\Admin\Contracts\LinkProcessorContract;

class BasicLinkProcessor implements LinkProcessorContract
{

    /** @var string */
    protected $labelField = null;

    /** @var array  */
    protected $linkArgsFields = [];

    /** @var string */
    protected $routeName = null;

    /**
     * BasicLinkProcessor constructor.
     * @param string $labelField
     * @param array $linkArgsFields
     * @param string $routeName
     */
    public function __construct(string $labelField, array $linkArgsFields, string $routeName)
    {
        $this->labelField = $labelField;
        $this->linkArgsFields = $linkArgsFields ?? [ 'fields' => [], 'static' => [] ];
        $this->routeName = $routeName;
    }

    public function generateLink($payload): array
    {
        if (!is_array($payload)) {
            $payload = $payload->toArray();
        }

        $label = array_get($payload, $this->labelField);
        $routeArgs = collect($this->linkArgsFields['fields'])
            ->map(function ($item) use ($payload) {
//                dd($item);
                $mapped = [];
                $mapped[$item] = array_get($payload, $item);
                return $mapped;
            })
            ->collapse()
            ->merge($this->linkArgsFields['static'])
            ->toArray();
        $url = route($this->routeName, $routeArgs);

        return [
            'url' => $url,
            'label' => $label
        ];
    }

}
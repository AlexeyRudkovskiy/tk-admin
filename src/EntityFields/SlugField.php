<?php
/**
 * Created by PhpStorm.
 * User: alexey
 * Date: 07.08.18
 * Time: 23:45
 */

namespace ARudkovskiy\Admin\EntityFields;


use Cocur\Slugify\Slugify;
use Illuminate\Http\Request;

class SlugField extends TextField
{

    public function handleRequest(Request $request, $entityObject)
    {
        $originalFieldName = $this->getOption('config.field');
        $isManually = $this->getOption('config.manually');
        $originalValue = $request->get($originalFieldName);
        $slugify = new Slugify($this->getOption('config'));
        $slug = $slugify->slugify($originalValue);

        $appendix = $this->getEntity()->getObject()->where('title', $originalValue)->get();
        if ($appendix->count() > 0) {
            $appendixSelf = $appendix->filter(function ($record) use ($entityObject) {
                return $record->id !== $entityObject->id;
            });
            if ($appendixSelf->count() > 0) {
                $slug .= '-' . $appendixSelf->count();
            }
        }

        $currentSlug = $entityObject->{$this->getName()};
        $manualSlug = $slugify->slugify($request->get($this->getName()));
        if (($manualSlug !== $currentSlug || $currentSlug === $manualSlug) && !empty($manualSlug)) {
            $this->value = $manualSlug;
        } else {
            $this->value = $slug;
        }
    }

    public function getDefaultOptions(): array
    {
        return array_merge(parent::getDefaultOptions(), [
            'config' => [
                'trim' => true,
                'separator' => true,
                'lowercase' => true
            ]
        ]);
    }


}
<?php
/**
 * Created by PhpStorm.
 * User: alexey
 * Date: 20.07.18
 * Time: 22:17
 */

namespace ARudkovskiy\Admin\Contracts;


use Illuminate\Http\Request;
use Illuminate\Support\Collection;

interface Entity
{

    public function getEntityClass(): string;

    public function getFields(): Collection;

    public function registerFields(): array;

    public function getAdditionalTableColumns(): array;

    public function getTranslations(): array;

    /**
     * @param string $key
     * @return string
     */
    public function translate(string $key): string;

    public function getShortName(): string;

    public function getId(): int;

    public function getIcon(): string;

    public function getObject();

    public function getField(string $fieldName);

    public function getSection(): string;

    public function getCustomFormView(): string;

    public function getUniqueIdentifier(): string;

    public function isMenuable(): bool;

    public function isRoutable(): bool;

    public function process($object);

    public function handleRequest(Request $request, bool $isAfterSave = false);

    public function validateRequest(string $type): bool;

    public function create();

    public function createAndSave();

    public function save();

    public function delete();

    public function fromObject($object);

}

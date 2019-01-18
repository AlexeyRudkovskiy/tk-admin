<?php

namespace ARudkovskiy\Admin\Contracts;

interface StorageServiceContract {

    public function createBreadcrumbs(string $path): array;

    public function encodeFolder(string $folder): string;

    public function decodeFolder(string $encoded): string;

    public function getDefaultFolder(): string;

}

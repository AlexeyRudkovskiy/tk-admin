# Admin Dashboard generator
## Table of content
* [Intervention Image](#Intervention Image)
## Libraries
### Intervention Image

Installation using composer:

```bash
composer require intervention/image
```

Run artisan command:

```bash
php artisan vendor:publish --provider="Intervention\Image\ImageServiceProviderLaravel5"
```

Configure graphic driver in ``config/image.php``

Detailed instruction can be found on official website: http://image.intervention.io/


### Slugify

Installation using composer:

```bash
composer require cocur/slugify
```

### Laravel Scout MYSQL Driver

Installation using composer:

```bash
composer require yab/laravel-scout-mysql-driver
```

Add to .env:

```ini
SCOUT_DRIVER=mysql
```

Add to scout config file (scout.php)

```php
    'mysql' => [
        'mode' => 'NATURAL_LANGUAGE',
        'model_directories' => [app_path()],
        'min_search_length' => 0,
        'min_fulltext_search_length' => 4,
        'min_fulltext_search_fallback' => 'LIKE',
        'query_expansion' => false
    ]
```

Read detailed documentation [here](https://github.com/YABhq/laravel-scout-mysql-driver)

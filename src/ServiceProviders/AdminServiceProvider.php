<?php
/**
 * Created by PhpStorm.
 * User: alexey
 * Date: 20.07.18
 * Time: 21:21
 */

namespace ARudkovskiy\Admin\ServiceProviders;


use ARudkovskiy\Admin\Commands\CreateAdmin;
use ARudkovskiy\Admin\Container\AdminContainer;
use ARudkovskiy\Admin\Container\AdminContainerInterface;
use ARudkovskiy\Admin\Contracts\Entity;
use ARudkovskiy\Admin\Contracts\StorageServiceContract;
use ARudkovskiy\Admin\Contracts\UploadFileContract;
use ARudkovskiy\Admin\Entities\CategoryEntity;
use ARudkovskiy\Admin\Entities\MenuEntity;
use ARudkovskiy\Admin\Entities\UserEntity;
use ARudkovskiy\Admin\Events\EntitySaved;
use ARudkovskiy\Admin\Http\DashboardViewComposer;
use ARudkovskiy\Admin\Listeners\EntitySavedListener;
use ARudkovskiy\Admin\Services\StorageService;
use ARudkovskiy\Admin\Services\UploadFile;
use Illuminate\Support\ServiceProvider;

class AdminServiceProvider extends ServiceProvider
{

    protected $events = [
        EntitySaved::class => [
            EntitySavedListener::class
        ]
    ];

    public function register() {
        $entities = config('admin.entities');
        $entities = collect($entities)
            ->map(function ($entity) {
                return new $entity;
            });

        $this->app->singleton(AdminContainerInterface::class, function () use ($entities) {
            $adminContainer = new AdminContainer();
            $entities->each(function (Entity $entity) use ($adminContainer) {
                $adminContainer->registerEntity($entity);
            });
            return $adminContainer;
        });

        $this->app->bind(UploadFileContract::class, UploadFile::class);
        $this->app->bind(StorageServiceContract::class, StorageService::class);

        /** @var AdminContainerInterface $adminContainer */
        $adminContainer = $this->app->make(AdminContainerInterface::class);
        $adminContainer->registerEntity(new MenuEntity());
        $adminContainer->registerEntity(new UserEntity());
        $adminContainer->registerEntity(new CategoryEntity());
    }

    public function boot() {
        $this->loadRoutesFrom(__DIR__ . '/../../routes/web.php');
        $this->loadViewsFrom(__DIR__ . '/../../resources/views/', '@admin');
        $this->publishes([
            __DIR__ . '/../../config/config.php' => config_path('admin.php')
        ]);
        $this->mergeConfigFrom(__DIR__ . '/../../config/config.php', 'admin');

        $this->loadTranslationsFrom(__DIR__ . '/../../resources/lang', '@admin');

        $this->publishes([
            __DIR__ . '/../../resources/assets' => public_path('arudkovskiy/admin')
        ], 'public');
        $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');

        \Blade::directive('spaceless', function() {
            return '<?php ob_start() ?>';
        });

        \Blade::directive('endspaceless', function() {
            return "<?php echo preg_replace('/>\\s+</', '><', ob_get_clean()); ?>";
        });

        view()->composer('@admin::*', DashboardViewComposer::class);

        foreach ($this->events as $event => $listeners) {
            foreach ($listeners as $listener) {
                \Event::listen($event, function () use ($listener) {
                    $listenerInstance = new $listener();
                    call_user_func_array([ $listenerInstance, 'handle' ], func_get_args());
                });
            }
        }

        if ($this->app->runningInConsole()) {
            $this->commands([
                CreateAdmin::class
            ]);
        }
    }

}
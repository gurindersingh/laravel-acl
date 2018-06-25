<?php

namespace Gurinder\LaravelAcl;


use Illuminate\Support\Collection;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use Gurinder\LaravelAcl\Repositories\AclLedger;
use Gurinder\LaravelAcl\Repositories\AclRegistrar;
use Gurinder\LaravelAcl\Middlewares\CheckPermission;
use Gurinder\LaravelAcl\Contracts\AclLedgerContract;
use Gurinder\LaravelAcl\Contracts\AclRegistrarContract;

class AclServiceProvider extends ServiceProvider
{
    protected $packageName = 'acl';

    public function boot()
    {

        $this->publishes([
            __DIR__ . '/config/acl.php' => config_path('acl.php')
        ], 'acl::config');

        if (!class_exists('CreateAclTables')) {
            $timestamp = date('Y_m_d_His', time());
            $this->publishes([
                __DIR__ . '/Database/migrations/create_acl_tables.php.stub' => $this->app->databasePath() . "/migrations/{$timestamp}_create_acl_tables.php",
            ], 'acl::migrations');
        }

        $this->loadRoutesFrom(__DIR__ . '/Routes/web.php');

        $this->loadViewsFrom(__DIR__ . '/views', $this->packageName);

        $this->publishes([
            __DIR__ . '/views' => $this->app->resourcePath('views/vendor/acl')
        ], 'acl::views');

        $this->deleteCacheOnLogout();

        if(!App::runningInConsole()) {

            resolve(AclRegistrarContract::class)->registerPermissions();

            $this->app['router']->aliasMiddleware('checkPermission', CheckPermission::class);

        }

    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->addCollectionMacros();

        $this->mergeConfigFrom(__DIR__ . '/config/acl.php', 'acl');

        $this->app->singleton(AclLedgerContract::class, AclLedger::class);

        $this->app->singleton(AclRegistrarContract::class, AclRegistrar::class);
    }

    protected function deleteCacheOnLogout()
    {
        Event::listen(Logout::class, function ($event) {
            resolve(AclLedgerContract::class)->resetUserAcl($event->user);
        });
    }

    protected function addCollectionMacros()
    {
        Collection::make(glob(__DIR__ . '/CollectionMacros/*.php'))
            ->mapWithKeys(function ($path) {
                return [$path => pathinfo($path, PATHINFO_FILENAME)];
            })
            ->reject(function ($macro) {
                return Collection::hasMacro($macro);
            })
            ->each(function ($macro, $path) {
                require_once $path;
            });
    }

}

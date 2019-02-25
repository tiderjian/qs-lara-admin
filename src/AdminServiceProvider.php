<?php

namespace Qs\Admin;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use Qs\Admin\Console\InstallCommand;
use Qs\Admin\Console\UninstallCommand;
use Qs\Admin\Console\UnpublishCommand;
use Qs\Admin\Middleware\RegisterExtends;

class AdminServiceProvider extends ServiceProvider
{

    const MIDDLEWARE_EXTEND = 'Qsadmin.extend';
    /**
     * Boot the service provider.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([__DIR__.'/../resources/lang' => resource_path('lang')], 'Qs-admin-lang');
            $this->publishes([__DIR__.'/../database/migrations' => database_path('migrations')], 'Qs-admin-migrations');
            $this->publishes([__DIR__.'/../resources/views' => resource_path('views')], 'Qs-admin-views');
            $this->publishes([__DIR__.'/../resources/assets' => public_path('vendor/qs-admin')], 'Qs-admin-assets');
        }

        $this->registerView();

        $this->loadRoutesFrom(__DIR__.'/../routes/qsadmin.php');
    }

    protected function registerView(){
        Event::listen("creating: admin::partials.footer", function($view){
            $view->setPath(resource_path('views/qsadmin/partials/footer.blade.php'));
        });

        Event::listen("creating: admin::login", function($view){
            $view->setPath(resource_path('views/qsadmin/login.blade.php'));
        });
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerCommands();
        $this->registerMiddleware();
    }

    protected function registerMiddleware(){
        app('router')->aliasMiddleware(self::MIDDLEWARE_EXTEND, RegisterExtends::class);

        if(app('router')->hasMiddlewareGroup('admin')){
            $adminMiddleGroup = app('router')->getMiddlewareGroups()['admin'];
            array_push($adminMiddleGroup, self::MIDDLEWARE_EXTEND);
            app('router')->middlewareGroup('admin', $adminMiddleGroup);
        }

    }


    protected function registerCommands(){
        if($this->app->runningInConsole()){
            $this->commands(InstallCommand::class);
            $this->commands(UninstallCommand::class);
            $this->commands(UnpublishCommand::class);
        }
    }

}

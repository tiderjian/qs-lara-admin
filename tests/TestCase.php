<?php
namespace Qs\Tests;

use Illuminate\Foundation\Testing\Concerns\InteractsWithConsole;
use Laravel\BrowserKitTesting\TestCase as BaseTestCase;
use Illuminate\Support\Facades\File;

class TestCase extends BaseTestCase {

    use InteractsWithConsole;

    public function createApplication()
    {
        $this->baseUrl = env("APP_URL",'http://localhost');
        $app = require __DIR__.'/../vendor/laravel/laravel/bootstrap/app.php';

        $app->booting(function () {
            $loader = \Illuminate\Foundation\AliasLoader::getInstance();
            $loader->alias('Admin', \Encore\Admin\Facades\Admin::class);
        });

        $app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

        $app->register('Encore\Admin\AdminServiceProvider');
        $app->register('Qs\Admin\AdminServiceProvider');

        return $app;
    }

    protected function setUp(){

        parent::setUp();

        $uses = array_flip(class_uses_recursive(static::class));

        if (isset($uses[Install::class])) {
            $this->install();
        }

        if (isset($uses[Login::class])) {
            $this->login();
        }

        if (file_exists($routes = admin_path('routes.php'))) {
            require $routes;
        }

        $this->makeInstalledJson();
    }

    protected function tearDown(){
        $uses = array_flip(class_uses_recursive(static::class));

        if (isset($uses[Uninstall::class])) {
            $this->uninstall();
        }

        $this->clearInstalledJson();

        parent::tearDown();
    }

    protected function makeInstalledJson(){
        $composerPath = app("path.base") . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'composer';

        if(File::isDirectory($composerPath) === false){
            File::makeDirectory($composerPath, 0755, true);
        }

        File::copy(join(DIRECTORY_SEPARATOR, [__DIR__, '..', 'vendor', 'composer', 'installed.json']),$composerPath . DIRECTORY_SEPARATOR . 'installed.json');
    }

    protected function clearInstalledJson(){
        File::deleteDirectory(app("path.base") . DIRECTORY_SEPARATOR . 'vendor');
    }
}
<?php
namespace Qs\Tests\Console;

use Encore\Admin\AdminServiceProvider;
use Illuminate\Foundation\Testing\TestCase as ConsoleTestCase;
use Qs\Tests\TestCase;

class UnpublishCommandTest extends TestCase {

    public function testUnpublish(){
        $this->artisan('vendor:publish', [
            '--provider' => AdminServiceProvider::class,
            '--tag' => ['laravel-admin-config']
        ]);
        $this->assertFileExists(config_path('admin.php'));
        $this->artisan('QS:unpublish', [
            '--provider' => AdminServiceProvider::class,
            '--tag' => 'laravel-admin-config'
        ]);
        $this->assertFileNotExists(config_path('admin.php'));

        $this->artisan('vendor:publish', [
            '--provider' => AdminServiceProvider::class,
            '--tag' => ['laravel-admin-config', 'laravel-admin-assets']
        ]);
        $this->assertFileExists(config_path('admin.php'));
        $this->assertFileExists(public_path('vendor/laravel-admin/jquery-pjax/jquery.pjax.js'));
        $this->artisan('QS:unpublish', [
            '--provider' => AdminServiceProvider::class,
            '--tag' => ['laravel-admin-config', 'laravel-admin-assets']
        ]);
        $this->assertFileNotExists(config_path('admin.php'));
        $this->assertFileNotExists(public_path('vendor/laravel-admin/jquery-pjax/jquery.pjax.js'));

        $this->artisan('vendor:publish', [
            '--provider' => AdminServiceProvider::class
        ]);
        $this->assertFileExists(config_path('admin.php'));
        $this->assertFileExists(public_path('vendor/laravel-admin/jquery-pjax/jquery.pjax.js'));
        $this->assertFileExists(resource_path('lang/zh-CN/admin.php'));

        $this->artisan('QS:unpublish', [
            '--provider' => AdminServiceProvider::class
        ]);

        $this->assertFileNotExists(config_path('admin.php'));
        $this->assertFileNotExists(public_path('vendor/laravel-admin/jquery-pjax/jquery-pjax.js'));
        $this->assertFileNotExists(resource_path('lang/zh-CN/admin.php'));
        $this->assertFileExists(resource_path('lang/en/auth.php'));

    }
}
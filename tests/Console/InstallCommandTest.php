<?php
namespace Qs\Tests\Console;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Qs\Tests\Install;
use Qs\Tests\TestCase;
use Qs\Tests\Uninstall;

class InstallCommandTest extends TestCase {

    use RefreshDatabase, Install, Uninstall;

    public function testInstall(){
        $this->assertEquals("zh-CN",$this->app['config']->get("app.locale"));
        $this->seeInDatabase('sysconf', ['name' => 'group_list']);
    }

}
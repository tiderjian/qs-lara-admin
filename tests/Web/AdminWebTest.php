<?php
namespace Qs\Tests\Web;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Qs\Tests\Install;
use Qs\Tests\Login;
use Qs\Tests\TestCase;
use Qs\Tests\Uninstall;

class AdminWebTest extends TestCase{
    use RefreshDatabase, Install, Login, Uninstall;

    public function testMenuTrans(){
        $cnFile = join(DIRECTORY_SEPARATOR, [__DIR__, '..', '..', 'resources', 'lang', 'zh-CN', 'admin.php']);

        $zh_CN = require $cnFile;

        $this->visit('admin')->see($zh_CN['menu_titles']['admin']);
    }

    public function testFooter(){
        $this->visit('admin')->see("QA Version");
    }
}
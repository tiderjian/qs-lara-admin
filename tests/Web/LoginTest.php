<?php
namespace Qs\Tests\Web;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Qs\Tests\Install;
use Qs\Tests\TestCase;
use Qs\Tests\Uninstall;

class LoginTest extends TestCase{
    use RefreshDatabase, Install, Uninstall;

    public function testLogin(){
        $this->visit('admin/auth/login')->see("技术驱动");
    }
}
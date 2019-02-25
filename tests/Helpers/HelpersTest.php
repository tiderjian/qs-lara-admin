<?php
namespace Qs\Tests\Helpers;

use Illuminate\Support\Str;
use Qs\Tests\TestCase;


class HelpersTest extends TestCase {

    public function test_is_value_empty()
    {
        $this->assertFalse(is_value_empty(123));
        $this->assertFalse(is_value_empty('123'));
        $this->assertFalse(is_value_empty([1,2,3]));
        $this->assertTrue(is_value_empty(null));
        $this->assertFalse(is_value_empty('null'));
        $this->assertTrue(is_value_empty(''));
        $this->assertTrue(is_value_empty([]));
        $this->assertFalse(is_value_empty('0'));
        $this->assertFalse(is_value_empty(0));
    }

    public function test_replace_config(){

        $this->assertTrue(replace_config("app.php", "locale", "zh-CN"));
        $config = require config_path("app.php");
        $this->assertEquals("zh-CN", $config['locale']);
        $this->assertFalse(replace_config("app.php", "locale1", "123"));

        replace_config("app.php", "locale", "en");

        $this->expectException("Illuminate\Contracts\Filesystem\FileNotFoundException");
        replace_config("app1.php", "locale", "123");
    }

    public function test_package_version(){

        $version = package_version("encore/laravel-admin");
        $this->assertIsString($version);
        $version = package_version(Str::random());
        $this->assertTrue(is_null($version));

        $this->clearInstalledJson();

        $this->expectException("Illuminate\Contracts\Filesystem\FileNotFoundException");
        package_version("encore/laravel-admin");
    }


}
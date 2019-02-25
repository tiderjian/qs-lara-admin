<?php
namespace Qs\Tests\Console;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Qs\Tests\Install;
use Qs\Tests\TestCase;

class UninstallCommandTest extends TestCase {
    use RefreshDatabase, Install;

    function testUninstall(){
        $this->artisan("QS:uninstall");

        $this->assertDirectoryNotExists(config('admin.directory'));
        $config = require config_path("app.php");
        $this->assertEquals("en", $config['locale']);
        $this->assertFileNotExists(config_path("admin.php"));
        $this->assertFileNotExists(resource_path('lang/en/admin.php'));
        $this->assertFileNotExists(database_path('migrations/2016_01_04_173148_create_admin_tables.php'));
        $this->assertDirectoryNotExists(public_path('vendor'));
        $this->assertFileNotExists(database_path('migrations/2019_01_14_065132_create_sysconf_table.php'));
        $tables = DB::connection()->getDoctrineSchemaManager()->listTableNames();

        $this->assertEquals(1, count($tables));
        $this->assertEquals("migrations", $tables[0]);
    }
}
<?php
namespace Qs\Admin\Console;

use Encore\Admin\AdminServiceProvider;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Qs\Admin\AdminServiceProvider as QsAdminServiceProvider;

class UninstallCommand extends Command{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'QS:uninstall';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Uninstall the QsAdmin package';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        if(replace_config("app.php", "locale", "en")){
            $this->info("revert locale to 'en'");
        }

        $this->info('Database reset');
        $this->call('migrate:reset');

        $this->call("QS:unpublish", [
            '--provider' => AdminServiceProvider::class
        ]);
        $this->call("QS:unpublish", [
            '--provider' => QsAdminServiceProvider::class
        ]);

        $adminDir = config('admin.directory');

        if(File::deleteDirectory($adminDir)){
            $this->info("Clear Admin directory.");
        }

        $this->info("Uninstall complete.");
    }


}
<?php
namespace Qs\Admin\Console;

use Encore\Admin\AdminServiceProvider;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Qs\Admin\AdminServiceProvider as QsAdminServiceProvider;

class InstallCommand extends Command{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'QS:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install the QsAdmin package';


    protected $filesystem;

    public function __construct(Filesystem $filesystem)
    {
        parent::__construct();

        $this->filesystem = $filesystem;
    }


    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $lang = $this->choice('Please select the system language.', ['en', 'zh-CN'], 1);

        if(replace_config("app.php", "locale", $lang)){
            $this->getLaravel()['config']->set('app.locale', $lang);

            $this->info("Set locale to {$lang}");
        }

        $this->info("Start to install laravel-admin.");

        $this->laravelAdminInstall();

        $this->info("Start to install Qs-admin.");

        $this->call("vendor:publish", ['--provider' => QsAdminServiceProvider::class, '--force' => true]);
        $this->call('migrate');

        $this->info("Install finished!");
    }

    protected function laravelAdminInstall(){
        $this->call("vendor:publish", ['--provider' => AdminServiceProvider::class]);

        $this->getLaravel()['config']->set('admin', require config_path("admin.php"));

        $this->call('admin:install');
    }
}
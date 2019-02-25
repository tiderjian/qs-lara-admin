<?php
namespace Qs\Admin\Console;

use Illuminate\Console\Command;
use Illuminate\Support\ServiceProvider;
use Illuminate\Filesystem\Filesystem;
use League\Flysystem\Adapter\Local as LocalAdapter;
use League\Flysystem\Filesystem as Flysystem;
use League\Flysystem\MountManager;

class UnpublishCommand extends Command{

    /**
     * The console command signature.
     *
     * @var string
     */
    protected $signature = 'QS:unpublish
                    {--provider= : The service provider that has assets you want to publish}
                    {--tag=* : One or many tags that have assets you want to publish}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Unpublish assets which had published from vendor packages';

    /**
     * The provider to publish.
     *
     * @var string
     */
    protected $provider = null;

    /**
     * The tags to publish.
     *
     * @var array
     */
    protected $tags = [];

    /**
     * The filesystem instance.
     *
     * @var \Illuminate\Filesystem\Filesystem
     */
    protected $files;

    /**
     * @var array
     */
    protected $dirStack = [];

    /**
     * Create a new command instance.
     *
     * @param  \Illuminate\Filesystem\Filesystem  $files
     * @return void
     */
    public function __construct(Filesystem $files)
    {
        parent::__construct();

        $this->files = $files;
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle() : void
    {
        [$this->provider, $this->tags] = [
            $this->option('provider'), (array)$this->option('tag'),
        ];

        if (!$this->provider) {
            $this->error("Please support the provider.");
        }

        $this->info("Unpublish [{$this->provider}]");

        foreach ($this->tags ?: [null] as $tag) {
            $this->info("");
            if ($tag) {
                $this->info("tag [{$tag}]:");
            }
            is_null($tag) ? $this->unpublishTag() : $this->unpublishTag($tag);
        }
        $this->info("");
        $this->info('Unpublishing complete.');
    }

    /**
     * Unpublishes the assets for a tag.
     *
     * @param  string  $tag
     * @return void
     */
    protected function unpublishTag(string $tag = '') : void
    {
        foreach ($this->pathsToPublish($tag) as $from => $to) {
            $this->unpublishItem($from, $to);
        }
    }


    /**
     * Get all of the paths to publish.
     *
     * @param  string  $tag
     * @return array
     */
    protected function pathsToPublish(string $tag) : array
    {
        return ServiceProvider::pathsToPublish(
            $this->provider, $tag
        );
    }

    /**
     * Unpublish the given item which had published.
     *
     * @param  string  $from
     * @param  string  $to
     * @return void
     */
    protected function unpublishItem(string $from, string $to) : void
    {
        if ($this->files->isFile($from)) {
            $this->deleteFile($to);
        } elseif ($this->files->isDirectory($from)) {
            $this->unpublishDirectory($from, $to);
        } else {
            $this->error("Can't locate path: <{$from}>");
        }
    }

    /**
     * delete the file in the given path.
     *
     * @param  string  $to
     * @return void
     */
    protected function deleteFile(string $to) : void
    {
        if ($this->files->exists($to)) {
            $this->files->delete($to) ? $this->statusForDelFile($to, true) : $this->statusForDelFile($to, false);
        }
    }


    /**
     *  delete files which have published from the $from directory to $to directory
     *
     * @param string $from
     * @param string $to
     */
    protected function unpublishDirectory(string $from, string $to) : void
    {
        $this->initDelDirStack($to);

        $manager = new MountManager([
            'from' => new Flysystem(new LocalAdapter($from)),
            'to' => new Flysystem(new LocalAdapter($to)),
        ]);

        $toPrefix = self::normalize_path($to);
        foreach ($manager->listContents('from://', true) as $file) {
            if ($file['type'] === 'dir' && $manager->has('to://'.$file['path'])) {
                $this->pushDelDirStack($toPrefix . DIRECTORY_SEPARATOR . $file['path']);
            }

            if ($file['type'] === 'file' && $manager->has('to://'.$file['path'])) {
                $path = $to.DIRECTORY_SEPARATOR. $file['path'];
                $manager->delete('to://'.$file['path']) ? $this->statusForDelFile($path, true) : $this->statusForDelFile($path, false);
            }
        }
        $this->deleteEmptyDir();
        $this->line('<info>Clear all empty directories</info>');
    }

    protected function deleteEmptyDir() : void
    {
        while (null != ($dir = array_pop($this->dirStack))) {
            $flySystem = new Flysystem(new LocalAdapter(base_path(), LOCK_EX, LocalAdapter::SKIP_LINKS));
            if ($flySystem->has($dir) && !$flySystem->listContents($dir)) {
                $absolute_path = $flySystem->getAdapter()->getPathPrefix() .  $flySystem->getMetadata($dir)["path"];
                $flySystem->deleteDir($dir) ? $this->statusForDelDirectory($absolute_path, true) : $this->statusForDelDirectory($absolute_path, false);
            }
        }
    }

    protected function initDelDirStack(string $to) : void
    {
        $to = self::normalize_path($to);
        $toArr = explode('/', $to);
        $tmp = '';
        foreach ($toArr as $toItem) {
            $tmp = empty($tmp) ? $toItem : $tmp . DIRECTORY_SEPARATOR . $toItem;
            array_push($this->dirStack, $tmp);
        }
    }

    protected function pushDelDirStack(string $dir) : void
    {
        array_push($this->dirStack, $dir);
    }


    /**
     * @param string $to
     * @param bool $status
     */
    protected function statusForDelFile(string $to, bool $status) : void
    {
        $to = self::normalize_path($to);

        if ($status) {
            $this->line('<info>Deleted</info> <comment>['.$to.']</comment>');
        } else {
            $this->error("Failed to delete {$to}");
        }
    }

    /**
     * @param string $to
     * @param bool $status
     */
    protected function statusForDelDirectory(string $to, bool $status) : void
    {
        $to = self::normalize_path($to);

        if ($status) {
            $this->line('<info>Deleted directory</info> <comment>['.$to.']</comment>');
        } else {
            $this->error("Failed to delete directory {$to}");
        }
    }

    protected function normalize_path(string $path) : string
    {
        $to = str_replace(base_path(), '', $path);

        return \League\Flysystem\Util::normalizePath($to);
    }

}
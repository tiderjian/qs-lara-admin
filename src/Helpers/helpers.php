<?php
use Illuminate\Support\Facades\File;
use Illuminate\Support\Arr;

if (!function_exists('is_value_empty')) {
    /**
     * @param $value
     * @return bool
     */
    function is_value_empty($value)
    {
        if ($value === '0') {
            $value = (int)$value;
        }
        return empty($value) && $value !== 0;
    }
}

if(!function_exists('replace_config')){

    /**
     * Set a new value for config file
     *
     * @param string $configFileName
     * @param string $key
     * @param $newValue
     * @return false if file or key not exists, otherwise return true
     */
    function replace_config(string $configFileName, string $key,  $newValue){
        $appConfigContent = File::get(config_path($configFileName));

        $regexRule = "/['\"]{$key}['\"]\\s*?=>\s*?['\"][A-Za-z0-9_\\-]+['\"]/i";

        if($appConfigContent !== false && $newContent = preg_filter($regexRule, "'{$key}' => '{$newValue}'", $appConfigContent)){
            File::put(config_path($configFileName), $newContent);
            return true;
        }
        else{
            return false;
        }
    }
}

if(!function_exists('package_version')){

    function package_version(string $packageName){
        $composerInstallFile = app("path.base") . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'composer' . DIRECTORY_SEPARATOR . 'installed.json';

        $packages = FIle::get($composerInstallFile);
        if($packages === false){
            throw new \Illuminate\Contracts\Filesystem\FileNotFoundException();
        }

        $packages = json_decode($packages, true);

        $laPackage = Arr::first(Arr::where($packages, function($package) use($packageName){
            if($package['name'] == $packageName){
                return true;
            }
        }));

        return $laPackage['version'];
    }
}
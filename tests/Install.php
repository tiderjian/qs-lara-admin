<?php
namespace Qs\Tests;

trait Install{

    protected function install(){

        $this->artisan("QS:install")->expectsQuestion("Please select the system language.", "zh-CN");

        $this->loadAdminAuthConfig();
    }

    protected function loadAdminAuthConfig()
    {
        config(array_dot(config('admin.auth', []), 'auth.'));
    }
}
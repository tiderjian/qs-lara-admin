<?php
namespace Qs\Tests;

trait Uninstall{

    protected function uninstall(){
        $this->artisan("QS:uninstall");
    }
}
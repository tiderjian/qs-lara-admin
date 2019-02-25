<?php
namespace Qs\Tests;

use Encore\Admin\Auth\Database\Administrator;

trait Login{

    protected function login(){

        $this->be(Administrator::first(), 'admin');
    }
}
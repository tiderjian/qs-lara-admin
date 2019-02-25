<?php
namespace Qs\Admin\Models;

class Sysconf extends Model {

    protected $casts =[
        'extra' => 'json',
        'value' => 'json'
    ];

}
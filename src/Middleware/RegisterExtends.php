<?php
namespace Qs\Admin\Middleware;

use Closure;
use Encore\Admin\Form;
use Qs\Admin\Form\Field\MultipleFile;
use Qs\Admin\Form\Field\MultipleImage;

class RegisterExtends{

    public function handle($request, Closure $next){
        Form::extend("multipleFile", MultipleFile::class);
        Form::extend("multipleImage", MultipleImage::class);
        return $next($request);
    }
}
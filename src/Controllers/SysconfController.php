<?php
namespace Qs\Admin\Controllers;

use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Form\Row;
use Encore\Admin\Layout\Content;
use Illuminate\Routing\Controller;
use Qs\Admin\Models\Sysconf;

class SysconfController extends Controller{

    use HasResourceActions;

    public function index(Content $content)
    {
        $form = new Form();
        $form->latlong('latitude', 'longitude', '经纬度选择');

// 设置地图高度
        $form->latlong('latitude', 'longitude', '经纬度')->height(500);

// 设置默认值
        $form->latlong('latitude', 'longitude', '经纬度')->default(['lat' => 90, 'lng' => 90]);
        return $content
            ->header(trans('admin.menu_titles.sys_conf'))
            ->description(trans('admin.list'))
            ->row($form);

        //$sysconf = Sysconf::where('name', 'group_list')->firstOrFail();

    }
}
<?php


namespace App\Admin\Extensions\tools;


use Encore\Admin\Grid\Tools\AbstractTool;

class Backforwad extends AbstractTool
{
    public function render()
    {
        return <<<HTML
<div class="btn-group pull-right" style="margin-right: 5px">
    <a href="javascript:history.back()" class="btn btn-sm btn-default" title="返回"><i class="fa fa-arrow-left"></i><span class="hidden-xs">&nbsp;返回</span></a>
</div>
HTML;
    }
}
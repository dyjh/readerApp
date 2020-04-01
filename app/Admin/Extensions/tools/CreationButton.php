<?php


namespace App\Admin\Extensions\tools;


use Encore\Admin\Grid\Tools\AbstractTool;

class CreationButton extends AbstractTool
{
    /**
     * @var string
     */
    private $url;

    /**
     * CreationButton constructor.
     * @param string $url
     */
    public function __construct(string $url)
    {
        $this->url = $url;
    }

    /**
     * {@inheritdoc}
     */
    public function render()
    {
        return <<< HTML
<div class="btn-group pull-right grid-create-btn" style="margin-right: 10px">
    <a href="{$this->url}" class="btn btn-sm btn-success" title="新增">
        <i class="fa fa-plus"></i><span class="hidden-xs">&nbsp;&nbsp;新增</span>
    </a>
</div>
HTML;

    }
}
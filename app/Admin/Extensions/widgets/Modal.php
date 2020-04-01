<?php


namespace App\Admin\Extensions\widgets;


use Encore\Admin\Admin;
use Illuminate\Contracts\Support\Renderable;

class Modal implements Renderable
{
    /**
     * @var string
     */
    private $title;

    /**
     * @var string
     */
    private $index;
    private $content;

    /**
     * Modal constructor.
     * @param string $index
     * @param string $title
     * @param $content
     */
    public function __construct(string $index, string $title, $content)
    {
        $this->index = $index;
        $this->title = $title;
        $this->content = $content;
    }

    private function script()
    {
        return <<<JS
$('button[type="submit"]').click(function () {
    setTimeout(function() {
        $('#{$this->index}').modal('hide');
        $('.modal-backdrop').removeClass("in");
        $('.modal-backdrop').addClass("out");
        $('.modal-backdrop').remove();
    }, 1000);
});
JS;
    }

    public function render()
    {
        Admin::script($this->script());
        return <<<HTML
<div class="modal fade" id="{$this->index}">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">$this->title</h4>
            </div>
            <div class="modal-body load_modal">
                $this->content
            </div>          
        </div>
    </div>
</div>
HTML;
    }
}
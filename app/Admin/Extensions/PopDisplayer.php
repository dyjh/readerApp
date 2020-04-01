<?php


namespace App\Admin\Extensions;


use App\Admin\Extensions\widgets\ModalForm;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Grid\Displayers\AbstractDisplayer;

class PopDisplayer extends AbstractDisplayer
{

    /**
     * Display method.
     *
     * @param string $content
     * @return mixed
     */
    public function display($content = '')
    {
        $button = '';
        $title = '';
        Admin::script($this->script());
        if ($content instanceof \Closure) {
            $result = $content->call($this, $this->row);
            if ($result instanceof ModalForm) {
                $button = $result->getButtonName();
                $title = $result->getTitle();
                $content = $result->render();
            }
        }

        return $this->render($button, $title, $content);

    }

    private function script()
    {
        return <<<JS
$('button[type="submit"]').click(function () {
    $('#bx-modal-form-{$this->row->id}').modal('hide');
});
$('.bx-modal-form-active-{$this->row->id}').click(function () {
    $('#bx-modal-form-{$this->row->id}').modal('show');
})
JS;
    }

    private function render(array $button, string $title, string $content)
    {
        $enabled = '';
        $btnName = $button['name'] ?? 'name';
        $btnClass = $button['enabled'] ?? true;
        if (!$btnClass) {
            $enabled = "disabled='disabled'";
        }
        return <<<HTML
<button type="button"
    class="btn btn-xs btn-primary bx-modal-form-active-{$this->row->id}" $enabled
    title="test"
    data-container="body"
    data-toggle="modal"
    data-content="{$this->value}">
  $btnName
</button>

<div class="modal fade" id="bx-modal-form-{$this->row->id}">
<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title">$title</h4>
        </div>
        <div class="modal-body load_modal">
            $content
        </div>          
    </div>
</div>
</button>
HTML;
    }
}
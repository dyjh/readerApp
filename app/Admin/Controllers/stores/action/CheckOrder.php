<?php
namespace App\Admin\Controllers\stores\action;

use Encore\Admin\Admin;

class CheckOrder
{
    protected $id;

    public function __construct($id)
    {
        $this->id = $id;
    }

    protected function script()
    {
        return <<<SCRIPT

$('.check-draw-money').on('click', function () {
     $.ajax({
        type : "POST",
        url : "../admin/checkOrder",
        dataType : "json",
        data : {
            'draw_money_id':$(this).data('id'),
            'type':'check'
        },
        success : function(test) {
            window.location.reload();
        },
    });
});

SCRIPT;
    }

    protected function render()
    {
        Admin::script($this->script());

        return "<a class='btn btn-xs btn-success check-draw-money' data-id='{$this->id}'>审核</a>";
    }

    public function __toString()
    {
        return $this->render();
    }
}

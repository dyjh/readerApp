<?php
namespace App\Admin\Controllers\stores\action;

use Encore\Admin\Admin;

class DeliverOrder
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
        type : "GET",
        url : "../admin/deliverOrder/"+$(this).data('id'),
        dataType : "json",
        data : {
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
       // Admin::script($this->script());

        return "<a class='btn btn-xs btn-success check-draw-money' href='/admin/deliverOrder/{$this->id}'>发货</a>";
    }

    public function __toString()
    {
        return $this->render();
    }
}

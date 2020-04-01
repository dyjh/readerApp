<?php


namespace App\Admin\Controllers\config\forms;


use App\Common\Definition;
use App\Models\config\Config;
use Encore\Admin\Widgets\Form;
use Illuminate\Http\Request;

class Protocol extends Form
{
    public $title = '用户协议';

    private $module = Definition::CONFIG_MODULE_PROTOCOL;

    /**
     * Handle the form request.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request)
    {
        $content = $request->post('protocol');
        Config::batchSet($this->module, $content);
        admin_toastr('用户协议修改成功');

        return back();
    }

    /**
     * Build a form here.
     */
    public function form()
    {
        $content = Config::get($this->module, 'content');

        $this->editor('protocol.content', '协议内容')
            ->default($content)
            ->rules('required');
    }

    /**
     * The data of the form.
     *
     * @return array $data
     */
    public function data()
    {
        return [

        ];
    }
}
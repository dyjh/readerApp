<?php


namespace App\Admin\Controllers\config\forms;


use App\Common\Definition;
use App\Models\config\Config;
use Encore\Admin\Widgets\Form;
use Illuminate\Http\Request;

class Bulletin extends Form
{
    public $title = '公告设置';

    private $module = Definition::CONFIG_MODULE_BULLETIN;

    /**
     * Handle the form request.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request)
    {
        $bulletins = $request->post('bulletin');
        Config::batchSet($this->module, $bulletins);
        admin_toastr('公告修改成功');

        return back();
    }

    /**
     * Build a form here.
     */
    public function form()
    {

        $bulletins = Config::get($this->module, 'bulletins', '');

        $this->textarea('bulletin.bulletins', '首页公告')
            ->rows(15)
            ->default($bulletins)
            ->rules('nullable|string|max:1000')
            ->help('多条公告回车隔开, 最多显示前50条');
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
<?php


namespace App\Admin\Controllers\config\forms;


use App\Admin\Common\Presuppose;
use App\Common\Definition;
use App\Models\config\Config;
use Encore\Admin\Widgets\Form;
use Illuminate\Http\Request;

class Rent extends Form
{
    public $title = '借阅设置';

    private $module = Definition::CONFIG_MODULE_RENT;

    /**
     * Handle the form request.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request)
    {
        $rents = $request->post('rent');
        Config::batchSet($this->module, $rents);
        admin_toastr('借阅配置修改成功');

        return back();
    }

    /**
     * Build a form here.
     */
    public function form()
    {

        $limitDays = Config::get($this->module, 'limit_days', '');
        $cost = Config::get($this->module, 'cost', '');
        $blockDays = Config::get($this->module, 'block_days', '');
        $blacklist = Config::get($this->module, 'blacklist', '');


        $this->text('rent.limit_days', '最长借阅天数')
            ->default($limitDays)
            ->rules('required|integer|min:1')
            ->help('最长借阅天数');
        $this->text('rent.cost', '超期扣书豆数(X)')
            ->default($cost)
            ->rules('required|integer|min:0')
            ->help('超过最长借阅天数, 每天扣书豆数(X)');

        $this->divider();

        $this->radio('rent.blacklist', '启用借书黑名单')
            ->options([
                '1' => '是',
                '0' => '否'
            ])
            ->default($blacklist);
        $this->text('rent.block_days', '最长还书期限(M)')
            ->default($blockDays)
            ->rules('required|integer|min:1')
            ->help('超过M天用户未还数, 进入借书黑名单, 用户将不能借入书籍, 需启用借书黑名单功能');


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
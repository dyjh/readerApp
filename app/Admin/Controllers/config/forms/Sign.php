<?php


namespace App\Admin\Controllers\config\forms;


use App\Common\Definition;
use App\Models\config\Config;
use Encore\Admin\Widgets\Form;
use Illuminate\Http\Request;

class Sign extends Form
{
    /**
     * The form title.
     *
     * @var string
     */
    public $title = '书豆设置';

    private $module = Definition::CONFIG_MODULE_SIGN;

    /**
     * Handle the form request.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request)
    {
        $signs = $request->post('sign');
        Config::batchSet($this->module, $signs);
        admin_toastr('书豆规则修改成功');

        return back();
    }

    /**
     * Build a form here.
     */
    public function form()
    {

        $firstAmount = Config::get($this->module, 'first_amount', config('sign.first_amount'));
        $continueDay = Config::get($this->module, 'continue_day', config('sign.continue_day'));
        $increaseAmount = Config::get($this->module, 'increase_amount', config('sign.increase_amount'));
        $usage = Config::get($this->module, 'usage');

        $this->text('sign.first_amount', '首次签到可领书豆(X)')
            ->rules('required|integer|min:0')
            ->default($firstAmount)
            ->help("用户首次签到领取的书豆数");
        $this->text('sign.continue_day', '书豆递增天数(M)')
            ->rules('required|integer|min:0')
            ->default($continueDay);
        $this->text('sign.increase_amount', '每日递增书豆数(Y)')
            ->rules('required|integer|min:0')
            ->default($increaseAmount)
            ->help("1~M 天每天递增 Y 书豆");

        $this->divider();
        $this->editor('sign.usage', '书豆使用规则')
            ->rules('required|max:1000')
            ->default($usage);
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
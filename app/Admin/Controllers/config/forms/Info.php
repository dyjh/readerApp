<?php


namespace App\Admin\Controllers\config\forms;


use App\Common\Definition;
use App\Models\config\Config;
use Encore\Admin\Widgets\Form;
use Illuminate\Http\Request;

class Info extends Form
{
    /**
     * The form title.
     *
     * @var string
     */
    public $title = '关于我们';

    private $module = Definition::CONFIG_MODULE_ABOUT;

    /**
     * Handle the form request.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request)
    {
        $about = $request->post('about');
        Config::batchSet($this->module, $about);
        admin_toastr('信息修改成功');

        return back();
    }

    /**
     * Build a form here.
     */
    public function form()
    {
        $contactType = Config::get($this->module, 'contact_type');
        $contactAddress = Config::get($this->module, 'contact_address');
        $profile = Config::get($this->module, 'profile');

        $this->text('about.contact_type', '社交平台')
            ->rules('required|max:20')
            ->default($contactType)
            ->help("社交平台");
        $this->text('about.contact_address', '联系方式')
            ->rules('required|max:20')
            ->default($contactAddress)
            ->help("联系方式");

        $this->divider();
        $this->editor('about.profile', '关于我们')
            ->default($profile)
            ->rules('required|max:1000');
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
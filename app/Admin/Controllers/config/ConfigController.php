<?php


namespace App\Admin\Controllers\config;


use App\Admin\Controllers\config\forms\Bulletin;
use App\Admin\Controllers\config\forms\Info;
use App\Admin\Controllers\config\forms\Protocol;
use App\Admin\Controllers\config\forms\Rent;
use App\Admin\Controllers\config\forms\Sign;
use App\Http\Controllers\Controller;
use Encore\Admin\Layout\Content;
use Encore\Admin\Widgets\Tab;

class ConfigController extends Controller
{
    public function index(Content $content)
    {
        return $content
            ->title('配置信息')
            ->body(Tab::forms([
                'bulletin' => Bulletin::class,
                'sign'    => Sign::class,
                'rent' => Rent::class,
                'info' => Info::class,
                'protocol' => Protocol::class
            ]));
    }
}
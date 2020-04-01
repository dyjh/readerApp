<?php

namespace App\Http\Controllers\api\platform;

use App\Common\Definition;
use App\Common\ResponseStatement;
use App\Common\traits\APIResponseTrait;
use App\Http\Controllers\Controller;
use App\Http\Resources\platform\BannerCollection;
use App\Http\Resources\platform\BannerContentResource;
use App\Models\config\Config;
use App\Models\platform\Banner;
use App\Services\platform\PlatformService;

class PlatformController extends Controller
{
    use APIResponseTrait;

    /**
     * @var PlatformService
     */
    private $platformService;

    /**
     * PlatformController constructor.
     * @param PlatformService $platformService
     */
    public function __construct(PlatformService $platformService)
    {
        $this->platformService = $platformService;
    }

    // 平台公告
    public function bulletins()
    {
        $bulletins = $this->platformService->getBulletins();

        return $this->json($bulletins);
    }

    // 轮播图列表
    public function banners()
    {
        $banners = $this->platformService->getBanners();

        return new BannerCollection($banners);
    }

    // 轮播图图文详情
    public function bannerDetail(Banner $banner)
    {
        if ($banner->getAttribute('type') != Definition::BANNER_TYPE_ARTICLE) {
            return $this->json('轮播图类型错误', ResponseStatement::STATUS_ERROR);
        }

        return $this->json(new BannerContentResource($banner));
    }

    // 关于我们
    public function about()
    {
        $about = $this->platformService->getAbout();

        return $this->json($about);
    }

    // 借书规则
    public function rentRule()
    {
        $rules = $this->platformService->getRentRules();

        return $this->json($rules);
    }

    // 书豆使用规则
    public function beanUsage()
    {
        $usage = $this->platformService->getBeanUsage();

        return $this->json($usage);
    }

    // 用户协议
    public function protocol()
    {
        $content = Config::get(Definition::CONFIG_MODULE_PROTOCOL, 'content');

        return $this->json($content);
    }
}

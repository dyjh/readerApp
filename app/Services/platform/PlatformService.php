<?php


namespace App\Services\platform;


use App\Common\Definition;
use App\Models\config\Config;
use App\Models\platform\Banner;

class PlatformService
{
    /**
     * @var Config
     */
    private $config;

    /**
     * PlatformService constructor.
     * @param Config $config
     */
    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    // 公告
    public function getBulletins()
    {
        $bulletinString = Config::get(Definition::CONFIG_MODULE_BULLETIN, 'bulletins');
        $raw = array_map('trim', preg_split('/[\r\n]+/s', $bulletinString));

        $bulletins = array_filter($raw, function ($item) {
            if (!empty($item)) {
                return $item;
            }
        });

        $indexed = [];
        foreach ($bulletins as $index => $bulletin) {
            $indexed[] = [
                'id' => $index + 1,
                'name' => $bulletin
            ];
        }
        return $indexed;
    }

    // 关于我们
    public function getAbout()
    {
        $contactType = Config::get(Definition::CONFIG_MODULE_ABOUT, 'contact_type');
        $contactAddress = Config::get(Definition::CONFIG_MODULE_ABOUT, 'contact_address');
        $profile = Config::get(Definition::CONFIG_MODULE_ABOUT, 'profile');

        return [
            'contact_method' => $contactType,
            'contact_address' => $contactAddress,
            'description' => $profile
        ];
    }

    // 借书规则
    public function getRentRules()
    {
        $limitDays = Config::get(Definition::CONFIG_MODULE_RENT, 'limit_days', '');
        $cost = Config::get(Definition::CONFIG_MODULE_RENT, 'cost', '');
        $blockDays = Config::get(Definition::CONFIG_MODULE_RENT, 'block_days', '');
        $blacklist = Config::get(Definition::CONFIG_MODULE_RENT, 'blacklist', '');


        return [
            'limit_days' => $limitDays,
            'cost' => $cost,
            'block_days' => $blockDays,
            'blacklist' => $blacklist
        ];
    }

    public function getBanners()
    {
        return Banner
            ::status()
            ->orderBy('sort')
            ->get();
    }

    public function getBeanUsage()
    {
        $usage = Config::get(Definition::CONFIG_MODULE_SIGN, 'usage');

        return compact('usage');
    }
}
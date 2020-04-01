<?php


namespace App\Admin\Common;


class Presuppose
{
    const SWITCH_STATES = [
        'on'  => ['value' => 1, 'text' => '启用', 'color' => 'success'],
        'off' => ['value' => 0, 'text' => '禁用', 'color' => 'default'],
    ];

    const SALE_STATES = [
        'on' =>  ['value' => 1, 'text' => '上架', 'color' => 'success'],
        'off' => ['value' => 0, 'text' => '下架', 'color' => 'default'],
    ];

    const ON_OFF_STATES = [
        'on'  => ['value' => 1, 'text' => '是', 'color' => 'success'],
        'off' => ['value' => 0, 'text' => '否', 'color' => 'default'],
    ];
}

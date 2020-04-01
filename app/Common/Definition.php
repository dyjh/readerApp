<?php


namespace App\Common;


class Definition
{
    const STATUS_ENABLE = 1;
    const STATUS_DISABLE = 0;
    const STATUS_EXAMPLES = [
        self::STATUS_DISABLE => '禁用',
        self::STATUS_ENABLE => '启用'
    ];

    const SHARED_BOOK_RENT_STATE_APPLYING = 1;
    const SHARED_BOOK_RENT_STATE_REJECTED = 2;
    const SHARED_BOOK_RENT_STATE_CANCELED = 3;
    const SHARED_BOOK_RENT_STATE_RENTING = 4;
    const SHARED_BOOK_RENT_STATE_RETURNING = 5;
    const SHARED_BOOK_RENT_STATE_RETURNED = 6;
    const SHARED_BOOK_RENT_STATE_EXPLAINS = [
        self::SHARED_BOOK_RENT_STATE_APPLYING => '借阅请求中',
        self::SHARED_BOOK_RENT_STATE_REJECTED => '已拒绝借阅',
        self::SHARED_BOOK_RENT_STATE_CANCELED => '已取消借阅',
        self::SHARED_BOOK_RENT_STATE_RENTING => '借阅中',
        self::SHARED_BOOK_RENT_STATE_RETURNING => '归还中',
        self::SHARED_BOOK_RENT_STATE_RETURNED => '已完成借阅',
    ];

    const LESSON_TAG_NEW = 1;
    const LESSON_TAG_HOT = 2;
    const LESSON_TAG_EXPLAINS = [
        self::LESSON_TAG_NEW => 'NEW',
        self::LESSON_TAG_HOT => 'HOT'
    ];

    const BANNER_TYPE_ARTICLE = 1;
    const BANNER_TYPE_LINK = 2;
    const BANNER_TYPE_LESSON = 3;
    const BANNER_TYPE_EXPLAINS = [
        self::BANNER_TYPE_ARTICLE => '图文',
        self::BANNER_TYPE_LINK => '链接',
        self::BANNER_TYPE_LESSON => '推荐课程'
    ];

    const BOOK_BEAN_CHANGE_BY_CHARGE = 1;
    const BOOK_BEAN_CHANGE_BY_SIGN = 2;
    const BOOK_BEAN_CHANGE_BY_RENT = 3;

    const CONFIG_MODULE_SIGN = 'sign';
    const CONFIG_MODULE_RENT = 'rent';
    const CONFIG_MODULE_ABOUT = 'about';
    const CONFIG_MODULE_BULLETIN = 'bulletin';
    const CONFIG_MODULE_PROTOCOL = 'protocol';
}
<?php

use Faker\Generator as Faker;

$factory->define(App\Models\shares\SharedBook::class, function (Faker $faker) {
    $books = [
        [
            'name' => '黑客与画家：硅谷创业之父Paul Graham文集',
            'author' => 'Paul Graham (作者) 阮一峰 (译者)',
            'publisher' => '人民邮电出版社',
            'isbn' => '9787115249494',
            'cover' => 'http://file.ituring.com.cn/SmallCover/0100d2f026a3a9ae5ba6',
            'desc' => '本书是硅谷创业之父Paul Graham 的文集，主要介绍黑客即优秀程序员的爱好和动机，讨论黑客成长、黑客对世界的贡献以及编程语言和黑客工作方法等所有对计算机时代感兴趣的人的一些话题。书中的内容不但有助于了解计算机编程的本质、互联网行业的规则，还会帮助读者了解我们这个时代，迫使读者独立思考。'
        ],
        [
            'name' => 'C现代编程：集成开发环境、设计模式、极限编程、测试驱动开发、重构、持续集成',
            'author' => '[日]花井志生 (作者) 杨文轩 (译者)',
            'publisher' => '中国工信出版社, 人民邮电出版社',
            'isbn' => '9787115417756',
            'cover' => 'http://file.ituring.com.cn/ScreenShow/01004668c4fff6aaef21',
            'desc' => '本书主要讲解如何将集成开发环境、设计模式、极限编程、测试驱动开发、重构、持续集成这些现代编程方法应用到C语言的嵌入式开发中去。即将服务器站点中的通用设计方法、工具的使用方法、开发方式等逐一“翻译”成为可以在C语言嵌入式开发过程中使用的方法。',
        ],
        [
            'name' => 'Python经典实例',
            'author' => '[美]史蒂文•F. 洛特 (作者) 闫兵 (译者)',
            'publisher' => '中国工信出版社, 人民邮电出版社',
            'isbn' => '9787115507174',
            'cover' => 'http://file.ituring.com.cn/SmallCover/1901fd7018a1b8e357af',
            'desc' => '本书是Python经典实例解析，采用基于实例的方法编写，每个实例都会解决具体的问题和难题。主要内容有：数字、字符串和元组，语句与语法，函数定义，列表、集、字典，用户输入和输出等内置数据结构，类和对象，函数式和反应式编程，Web服务，等等。',
        ],
        [
            'name' => '你不可不知的50个战争知识（修订版）',
            'author' => '［英］罗宾·克罗斯 (作者) 王喜 (译者)',
            'publisher' => '人民邮电出版社, 中国工信出版社',
            'isbn' => '9787115503558',
            'cover' => 'http://file.ituring.com.cn/SmallCover/1902f3fe575ee85e8fd4',
            'desc' => '本书通过50篇短小精干的短文，介绍了人类最持久的一种活动——战争。从古代战车、维京长船和城堡，到坦克、雷达、轰炸机，再到原子弹、无人机和网络战，作者形象地描绘了过去2500多年人类所发明的最重要的武器、防御工事及战术。',
        ]
    ];

    $book = $faker->randomElement($books);
    return [
        'school_id' => $faker->randomElement([10, 9, 6]),
        'grade_id' => $faker->randomElement([10, 9, 6]),
        'ban_id' => $faker->randomElement([10, 9, 6]),
        'name' => $book['name'],
        'author' => $book['author'],
        'publisher' => $book['publisher'],
        'isbn' => $book['isbn'],
        'cover' => $book['cover'],
        'desc' => $book['desc'],
    ];
});

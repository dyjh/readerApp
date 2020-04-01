<?php


namespace App\Admin\Extensions\widgets;


use Encore\Admin\Tree;

class TreeViewer extends Tree
{
    protected $view = [
        'tree'   => 'backend.trees.catalog',
        'branch' => 'backend.trees.chapter',
    ];
}
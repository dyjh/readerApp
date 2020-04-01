<?php

namespace App\Admin\Controllers;

use Encore\Admin\Controllers\AdminController;
use Illuminate\Http\Request;

class UploadController extends AdminController
{
    public function file(Request $request)
    {
        $data = [
            'errno'   => 1,
        ];

        $path = $request->file('file')->store(
        'files', 'admin'
        );

        if ($path) {
            $data['data'][] = asset('uploads/'.$path);
            $data['errno'] = 0;
        }

        return $data;
    }
}

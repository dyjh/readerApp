<?php


namespace App\Common\traits;


use App\Common\ResponseStatement;

trait APIResponseTrait
{
    public function json($data = '操作成功', int $status = ResponseStatement::STATUS_OK)
    {
        if ($status == ResponseStatement::STATUS_ERROR) {
            return response()->json([
                'status' => $status,
                'message' => $data
            ]);
        }

        return response()->json([
            'status' => $status,
            'data' => $data
        ]);
    }

    // 集合资源添加通用状态
    public function with($request)
    {
        return [
            'status' => ResponseStatement::STATUS_OK
        ];
    }
}

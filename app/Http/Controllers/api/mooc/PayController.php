<?php

namespace App\Http\Controllers\api\mooc;

use App\Common\ResponseStatement;
use App\Http\Requests\mooc\PayOrder;
use App\Models\mooc\Lesson;
use App\Models\mooc\StudentLessons;
use App\Repositories\mooc\LessonRepositoryEloquent;
use App\Services\mooc\Exceptions\LessonGeneratorException;
use App\Services\pay\OrderConfig;
use DB;

class PayController extends MoocController
{

    private $repository;

    public function __construct(LessonRepositoryEloquent $repository)
    {
        $this->repository = $repository;
    }

    /**
     * 支付
     */
    public function pay(Lesson $lesson, PayOrder $request)
    {
      //  throw_if(($order->statement !=0), LessonGeneratorException::class, 40005);
        $user = auth('api')->user();
        $check = StudentLessons::where([
            ['student_id', $user->id],
            ['lesson_id', $lesson->id],
            ['payment_statement', 1]
        ])->first();
        if ($check) {
            return $this->json('该课程已购买，请勿重复购买', ResponseStatement::STATUS_OK);
        }
        $type = $request->input('type', 'app');
        $platform = $request->input('platform', 'alipay');

        $data = $this->repository->pay($lesson, $platform, $type);

        return $this->json($data);
    }

    /**
     * 免费课程直接获取
     * @param Lesson $lesson
     * @param PayOrder $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getLessons(Lesson $lesson, PayOrder $request)
    {
        $user = auth('api')->user();
        $check = StudentLessons::where([
            ['student_id', $user->id],
            ['lesson_id', $lesson->id],
            ['payment_statement', 1]
        ])->first();
        if ($check) {
            return $this->json('该课程已获取，请勿重复获取', ResponseStatement::STATUS_ERROR);
        }
        $res = $this->repository->payWithNoMoney($lesson);
        if ($res['code'] == 1) {
            return $this->json();
        } else {
            return $this->json($res['msg'], $res['code']);
        }
    }

    /**
     * 支付宝
     */
    public function ali_notify_url()
    {
        $alipay =  (new OrderConfig('alipay'))->order();

        DB::beginTransaction();
        try{
            $data = $alipay->verify();

            if(!in_array($data->trade_status, ['TRADE_SUCCESS', 'TRADE_FINISHED'])) {
                return $alipay->success();
            }

            $this->repository->alipayNotify($data);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();

            \Yansongda\Pay\Log::error($e->getMessage());
        }

        return;
    }

    /**
     * 微信
     */
    public function wx_return_url()
    {
        $wxpay = (new OrderConfig('wxpay'))->order();

        DB::beginTransaction();
        try{
            $data = $wxpay->verify();

            $this->repository->wxpayNotify($data);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();

            \Yansongda\Pay\Log::error($e->getMessage());
        }

        return;
    }
}

<?php

namespace App\Repositories\mooc;

use App\Common\ResponseStatement;
use App\Models\abstracts\AbstractPayable;
use App\Models\baseinfo\Student;
use App\Services\pay\PayService;
use Carbon\Carbon;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Models\mooc\Lesson;
use App\Validators\Mooc\LessonValidator;

/**
 * Class LessonRepositoryEloquent.
 *
 * @package namespace App\Repositories\Mooc;
 */
class LessonRepositoryEloquent extends BaseRepository implements LessonRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Lesson::class;
    }

    public function user_lessons() :array
    {
        $lessons['live'] = Student::getUserLessons(1);
        $lessons['record'] = Student::getUserLessons(0);

        return $lessons;
    }

    public function pay(Lesson $lesson, string $platform, string $type)
    {
        $userOrder = $this->createOrder($lesson, $platform);

        return $this->orderPay($userOrder, $platform, $type);
    }

    public function payWithNoMoney(Lesson $lesson)
    {
        if ($lesson->price == 0) {
            \Auth::user()->lessons()->create([
                'lesson_id'         => $lesson->id,
                'payment_statement' => 1,
                'paid_price'        => $lesson->price,
                'trade_no'          => $this->getOrderTrade(),
                'payment_method'    => 3
            ]);
            return ['code' => ResponseStatement::STATUS_OK];
        } else {
            return ['code' => ResponseStatement::STATUS_ERROR, 'msg' => '该课程无法免费获取'];
        }
    }

    protected function orderPay(AbstractPayable $model, string $platform, string $type)
    {
        $payService = new PayService($model, $platform, $type);

        $order = $payService->getPayOrder();

        $request = $payService->getPayConfig('lesson');

        $result = $request->{$type}($order);

        $payService->created($result);

        return ['content' => $result->getContent()];
    }

    protected function createOrder(Lesson $lesson, $platform)
    {
        return \Auth::user()->lessons()->create([
                'lesson_id'         => $lesson->id,
                'payment_statement' => 0,
                'paid_price'        => $lesson->price,
                'trade_no'          => $this->getOrderTrade(),
                'payment_method'    => ($platform=='alipay') ? 1 : 2
            ]);
    }

    public function alipayNotify($data)
    {
        $this->updateOrder($data->out_trade_no, $data->trade_no);
    }

    public function wxpayNotify($data)
    {
        $this->updateOrder($data->out_trade_no, $data->transaction_id);
    }

    protected function updateOrder($out_trade_no, $trans_no)
    {
        $order = $this->model()::where(['trade_no'=> $out_trade_no, 'payment_statement'=> 0])->firstOrFail();

        $order->update(['trans_no'=> $trans_no, 'payment_statement' => 1]);

        $order->receipt()->latest()->update(['paid_at'=> Carbon::now(), 'paid'=> true]);
    }

    protected function getOrderTrade() :string
    {
        return  date('YmdHis') . str_pad(mt_rand(1, 99), 2, '0', STR_PAD_LEFT);
    }

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

}

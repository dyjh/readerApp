<?php


namespace App\Http\Controllers\api\mine;


use App\Common\traits\APIResponseTrait;
use App\Http\Requests\mine\BeanChargeRequest;
use App\Http\Resources\mine\BeanProductCollection;
use App\Models\baseinfo\ProductBean;
use App\Services\mine\BeanChargeService;

class BeanChargeController
{
    use APIResponseTrait;
    /**
     * @var BeanChargeService
     */
    private $chargeService;

    /**
     * BeanChargeController constructor.
     * @param BeanChargeService $chargeService
     */
    public function __construct(BeanChargeService $chargeService)
    {
        $this->chargeService = $chargeService;
    }

    // 书豆充值列表
    public function productList()
    {
        return new BeanProductCollection(ProductBean::paginate());
    }

    // 书豆充值
    public function charge(BeanChargeRequest $request)
    {
        $student = auth('api')->user();
        $paymethod = $request->post('paymethod', 'alipay');
        $productBeanId = $request->post('product_bean_id');

        /** @var ProductBean $productBeanModel */
        $productBeanModel = ProductBean::findOrFail($productBeanId);

        $data = $this->chargeService->chargeRequest($student, $productBeanModel, $paymethod);

        return $this->json($data);
    }

    public function alipayNotify()
    {
        return $this->chargeService->paidNotify('alipay');
    }

    public function wxpayNotify()
    {
        return $this->chargeService->wechatPaid('wechat');
    }
}
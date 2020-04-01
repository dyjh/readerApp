<?php

namespace App\Repositories\store;

use App\Models\abstracts\AbstractPayable;
use App\Models\stores\Order;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Interface OrderRepositoryRepository.
 *
 * @package namespace App\Repositories\Store;
 */
interface OrderRepositoryRepository extends RepositoryInterface
{
    public function refundOrder(array $request) :void;

    public function pay(AbstractPayable $order, string $type);

    public function alipayNotify($data);

    public function wxpayNotify($data);

}

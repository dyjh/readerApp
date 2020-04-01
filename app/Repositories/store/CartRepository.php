<?php

namespace App\Repositories\Store;

use App\Models\stores\Cart;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Interface CartRepository.
 *
 * @package namespace App\Repositories\Store;
 */
interface CartRepository extends RepositoryInterface
{
    public function createCart(array $request) :Cart;

}

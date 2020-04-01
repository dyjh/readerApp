<?php

namespace App\Repositories\Store;

use App\Models\baseinfo\Student;
use App\Models\stores\ProductBook;
use Illuminate\Support\Facades\Auth;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Models\stores\Cart;

/**
 * Class CartRepositoryEloquent.
 *
 * @package namespace App\Repositories\Store;
 */
class CartRepositoryEloquent extends BaseRepository implements CartRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Cart::class;
    }

    public function createCart(array $request) :Cart
    {
        $productItem = ProductBook::find($request['product_book_id']);
        $student_id = Auth::id();
        $cart = $this->model()::where(['product_book_id'=> $request['product_book_id'], 'student_id'=> $student_id])->first();
        $data = $productItem->only('name', 'sell_price', 'cover');
        if(!$cart)
        {
            $data['student_id'] = $student_id;
            $data['product_count'] = $request['product_count'];
            $data['product_book_id'] = $request['product_book_id'];
            $cart = $this->model()::create($data);
        }else{
            $cart->increment('product_count', $request['product_count']);
        }
        // 商品如果修改过内容，须更新购物车中内容
        if ($cart->name !== $productItem->name || $cart->price !== $productItem->sell_price || $cart->cover !== $productItem->cover)
            $cart->update($data);
        return $cart;
    }


    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

}

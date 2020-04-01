<?php

namespace App\Http\Controllers\api\store;

use App\Http\Resources\Store\CartResource;
use App\Models\stores\Cart;
use Illuminate\Http\Request;
use App\Http\Requests\store\CartRequest;
use App\Repositories\store\CartRepositoryEloquent;
use Illuminate\Support\Facades\Auth;

class CartController extends StoreController
{
    private $repository;

    public function __construct(CartRepositoryEloquent $repository)
    {
        $this->repository = $repository;
    }

    public function store(CartRequest $request)
    {
        $cart = $this->repository->createCart($request->only(['product_book_id', 'product_count']));
        return $this->json(CartResource::make($cart));
    }

    public function index()
    {
        return $this->json(CartResource::collection(Cart::with('product')->where('student_id', Auth::id())->sort()->get()));
    }

    public function update(Cart $cart, Request $request)
    {
        $request->validate([
            'product_count' => 'required|integer'
        ]);
        $cart && $cart->update($request->only('product_count'));
        return $this->json();
    }

    public function destroy(Request $request)
    {
        $request->validate([
            'ids' => 'required|array'
        ]);
        Cart::where('student_id', Auth::id())->whereIn('id', $request->get('ids'))->delete();
        return $this->json();
    }
}


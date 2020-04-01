<?php

namespace App\Http\Controllers\api\store;

use App\Http\Resources\Store\ProductBookCollection;
use App\Models\stores\ProductBook;
use App\Models\stores\ProductCategory;
use App\Http\Filters\Store\ProductBookFilter;
use App\Http\Resources\Store\CategoryResource;
use App\Http\Resources\Store\ProductBookResource;

class ProductBookController extends StoreController
{
    public function index(ProductBookFilter $filter)
    {
        return ProductBookCollection::make(ProductBook::with('category')->filter($filter)->sale()->sort()->paginate());
    }

    public function show(ProductBook $book)
    {
        return new ProductBookResource($book);
    }

    public function categories()
    {
        return $this->json(CategoryResource::collection(ProductCategory::status()->get()));
    }
}

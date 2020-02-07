<?php

namespace App\Http\Controllers\Api;

use App\Eloquent\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProductController extends Controller
{
    private $eloquent;

    /**
     * ProductController constructor.
     * @param Product $product
     */
    public function __construct(Product $product)
    {
        $this->eloquent = $product;
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $results = $this->eloquent->paginate(30);
        return response()->json($results->toArray());
    }

}

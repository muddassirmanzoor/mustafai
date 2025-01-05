<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserValidation;
use App\Http\Resources\ActivityResource;
use App\Http\Resources\ProductResource;
use App\Models\Admin\Category;
use App\Models\Admin\Product;
use App\Models\User\Activity;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     *get mustafai store products api
    */
    public function getMustafaiStore(Request $request)
    {
        $sortId=$request->sorting_filter;
        $productType=$request->product_type;
        $categoryId = $request->categoryId;
        $data = Product::with('productImages:id,product_id,file_name')->where('id','>',$request->lastProductId ?? 0)
                ->when($categoryId, function ($query, $categoryId) {
                    return $query->where('category_id', $categoryId);
                })
                ->when($productType, function ($query, $productType) {
                    $productType=$productType=='physical' ? 0 :1;
                    return $query->where('product_type', $productType);
                })
                ->active()
                // ->limit($request->limit ?? 8)
                ->when($sortId, function ($query, $sortId) {
                    $order = explode(' ',  $sortId);
                    return $query->orderBy($order[0], $order[1]);
                })
                // ->get()
                ->paginate($request->limit ?? 8)
                ->map(function ($product) {
                        $product->productImages->each(function ($image) {
                        $image->file_name = getS3File($image->file_name);
                    });
                    return $product;
                });

        $data = ProductResource::collection($data);
         return response()->json([
            'status'  => 1,
            'message' =>"success",
            'lastProductId'=>optional($data->first())->id,
            'data' =>$data,
         ]);
    }
    /**
     *get featured products api
    */
    public function getFeaturedProduct(Request $request)
    {
        $data = Product::with('productImages:id,product_id,file_name')->where('featured',1)
                ->get()->map(function ($product) {
                        $product->productImages->each(function ($image) {
                        $image->file_name = getS3File($image->file_name);
                    });
                    return $product;
                });

        $data = ProductResource::collection($data);
        return response()->json([
            'status'  => 1,
            'message' =>"success",
            'data' =>$data,
        ]);
    }
    /**
     *get product categories api
    */
    public function getProductCategories(Request $request)
    {
        $lang = $request->input('lang');
        $query = array_merge(getQuery($lang, ['name']),['id','status']);
        $Categories = Category::select($query)->where('status', 1)->get()->toArray();
        return response()->json([
            'status'  => 1,
            'message' =>"success",
            'data' =>$Categories,
        ]);
    }


}
